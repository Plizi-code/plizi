<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\CitiesCollection;
use App\Models\Geo\City;
use Illuminate\Http\Request;

class GeoController extends Controller
{

    /**
     * @param Request $request
     * @return CitiesCollection|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        if (!empty($search)) {
            $result = City::with('country', 'region')
                ->where('title_ru', 'LIKE', "%{$search}%")
                ->where('title_ua', 'LIKE', "%{$search}%")
                ->where('title_en', 'LIKE', "%{$search}%")
                ->orderBy('important', 'desc')
                ->limit(100)
                ->get();
            if (count($result)) {
                return new CitiesCollection($result);
            }
        }
        return response()->json(['message' => 'No found'], 200);
    }
}
