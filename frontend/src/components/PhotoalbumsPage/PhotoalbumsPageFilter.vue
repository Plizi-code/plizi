<template>
    <div id="photoalbumPageFilter"  class="bg-white-br20  col-12 d-flex flex-wrap flex-sm-nowrap align-items-center justify-content-between mb-4 pb-3 pb-sm-0">
        <nav class="videos-filter-links col-lg-7 nav mb-2 mb-sm-0 pt-2 pt-sm-0" role="tablist">
            <span class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4" :class="{ 'active': wMode === 'my' }" id="tabMyPhotoalbums" role="tab"
                  @click.stop="ontabChange">Мои альбомы</span>
        </nav>

        <div class="additionalBtns col-12 col-sm-5 d-flex justify-content-between justify-content-sm-end px-0  ">
            <template v-if="wMode === 'my'">
                <PhotoalbumCreateBlock></PhotoalbumCreateBlock>
            </template>
            <template v-else>
                <button type="button" @click.stop="onAttachBtnClick($event)"
                        class="btn plz-btn plz-btn-primary p-0 mx-auto mr-sm-0">
                    Добавить фотографию
                    <input type="file"
                           class="plz-text-editor-file-picker d-none"
                           ref="editorFiler"
                           multiple
                           @change="onSelect"/>
                </button>
            </template>
        </div>
    </div>
</template>

<script>
import PhotoalbumCreateBlock from "./PhotoalbumCreateBlock.vue";
import {docsExtensions, imagesExtensions} from "../../enums/FileExtensionEnums";
import {checkExtension, checkMimeType} from "../../utils/FileUtils";
import PliziAttachmentItem from "../../classes/PliziAttachmentItem";
import PliziAttachment from "../../classes/PliziAttachment";
import PliziCollection from "../../classes/PliziCollection";
import PliziPhotoAlbum from "../../classes/PliziPhotoAlbum.js";

export default {
    name: "PhotoalbumsPageFilter",
    components: {
        PhotoalbumCreateBlock
    },
    props: {
        photoAlbum: PliziPhotoAlbum,
    },
    data() {
        return {
            wMode: `my`,
            attachFiles: [],
            attachmentsData: (new PliziCollection()),
        }
    },
    methods: {
        onAttachBtnClick(ev){
            let $btn = null;
            if (ev.target.tagName.toUpperCase() === 'BUTTON') {
                $btn = $(ev.target);
            }
            else {
                $btn = $(ev.target).closest('button.attach-file');
            }

            const $file = $btn.find('input.plz-text-editor-file-picker');
            $file.click();
        },
        ontabChange() {
            if (this.$route.name !== 'PhotoalbumsListPage') {
                this.$router.push({ path: '/photoalbums-list' });
                this.wMode = 'album';
            }
        },
        onSelect() {
            this.addUploadAttachment([...this.$refs.editorFiler.files]);
        },

        async addUploadAttachment(picsArr) {
            const filesCount = picsArr.length + this.attachFiles.length;

            if (filesCount > this.maxFilesCount) {
                this.$alert(`
                <h4 class="text-white">Ошибка</h4>
                <div class="alert alert-danger">
                Превышен лимит загрузки файлов
                <br />
                Допустимый максимальный лимит файлов: <b class="text-success">${this.maxFilesCount}</b>
                </div>`, `bg-danger`, 30
                );
                return;
            }

            const allowExtensions = [...imagesExtensions, ...docsExtensions];

            for (const file of picsArr) {
                if (!checkExtension(file, allowExtensions) || !checkMimeType(file)) {
                    this.$alert(`
                    <h4 class="text-white">Ошибка</h4>
                    <div class="alert alert-danger">
                        Недопустимое расширение у файла <b>${file.name}</b>
                        <br />
                        Допустимые расширения файлов: <b class="text-success">${allowExtensions.join( ', ' )}</b>
                    </div>`,
                        `bg-danger`,
                        30
                    );

                    picsArr = picsArr.filter(foundFile => foundFile.name !== file.name);
                }
            }

            if (picsArr.length === 0)
                return;

            for (let file of picsArr) {
                const reader = new FileReader();
                reader.onload = () => {
                    const image = new PliziAttachmentItem(true, checkExtension(file, imagesExtensions), file.name);
                    image.isBlob = true;
                    image.fileBlob = reader.result;

                    this.$emit('uploadingImage', image);
                };

                reader.readAsDataURL(file);

                let apiResponse = [];
                const delay = ms => new Promise(resolve => setTimeout(resolve, ms));
                await delay(1000);
                    apiResponse = await this.$root.$api.$photoalbums.uploadImagesInPhotoAlbum(this.photoAlbum.id, [file]);

                    apiResponse.map((attItem) => {
                        this.$emit('addNewImages', attItem);
                    });
                }
        },
    },
    mounted() {
        if (this.$route.name !== 'PhotoalbumsListPage') {
            this.wMode = 'album';
        }
    }
}
</script>


