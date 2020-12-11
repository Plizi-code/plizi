<?php


namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Ramsey\Uuid\Uuid;
use Storage;

class S3UploadService
{
    /**
     * @param Model $model
     * @param $path
     * @param $files
     * @param string $visibility
     * @param array $config
     * @param array $additionalData
     * @return array
     * @throws Exception
     */
    public function uploadFiles(Model $model, $path, $files, $visibility = 'public', $config = [], $additionalData = [])
    {
        $config = $config ?: $this->getDefaultConfig();

        $uploaded = $this->multiUpload($path, $files, $visibility, $config);

        $attachment_ids = [];

        foreach ($uploaded as $data) {
            $data = array_merge($data, $additionalData);
            $attachment = $model::create($data);
            $attachment_ids[] = $attachment->id;
        }

        return $attachment_ids;
    }

    /**
     * @param $path
     * @param $files
     * @param string $visibility
     * @param array $config
     * @return array
     * @throws Exception
     */
    public function multiUpload($path, $files, $visibility = 'public', $config = [])
    {
        $config = $config ?: $this->getDefaultConfig();

        $result = [];
        foreach ($files['files'] as $file) {
            $result[] = $this->singleUpload($path, $file, $visibility, $config);
        }
        return $result;
    }

    /**
     * @return array
     */
    private function getDefaultConfig()
    {
        return [
            'normal' => [
                'size' => 600,
            ],
            'medium' => [
                'size' => 250,
            ],
            'thumb' => [
                'size' => 60,
            ],
        ];
    }

    /**
     * @param $file
     * @param $path
     * @param $visibility
     * @param array $config
     * @return array
     * @throws Exception
     */
    public function singleUpload($path, $file, $visibility = 'public', $config = [])
    {
        $config = $config ?: $this->getDefaultConfig();

        $imageName = Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
        $original = Storage::disk('s3')->put("$path/originals", $file, $visibility);

        $data = [
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
            'path' => $original,
            'mime_type' => $file->getClientMimeType(),
            'image_original_width' => null,
            'image_original_height' => null,
        ];
        if (@is_array(getimagesize($file))) {
            $original_img = Image::make($file);

            $data['image_original_width'] = $original_img->getWidth();
            $data['image_original_height'] = $original_img->getHeight();

            foreach ($config as $name => $conf) {
                $image = $this->prepareImage($file, $conf['size']);
                $subPath = "{$path}/{$name}s/{$imageName}";
                Storage::disk('s3')->put($subPath, $image->stream(), $visibility);
                $data["image_{$name}_path"] = $subPath;
                $data["image_{$name}_width"] = $image->getWidth();
                $data["image_{$name}_height"] = $image->getHeight();
            }
        } else {
            foreach ($config as $name => $conf) {
                $subPath = "{$path}/{$name}s/{$imageName}";
                $data["image_{$name}_path"] = $subPath;
                $data["image_{$name}_width"] = null;
                $data["image_{$name}_height"] = null;
            }
        }
        return $data;
    }

    /**
     * @param $image
     * @param $size
     * @return InterventionImage
     */
    public function prepareImage($image, $size) : InterventionImage {
        $new_image = Image::make($image);
        if (is_array($size)) {
            [$width, $height] = $size;
            if ($width && $height) {
                $new_image->fit($width, $height, static function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $new_image;
            }
        }
        if($new_image->width() > $new_image->height()) {
            $width = $size;
            $height = null;
        } else if($new_image->width() < $new_image->height()){
            $width = null;
            $height = $size;
        } else {
            $width = $size;
            $height = $size;
        }
        $new_image->resize($width, $height, static function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $new_image;
    }
}
