<template>
    <div v-if="photoAlbum" class="photoalbum-page-description text-center">
        <h6 class="photoalbum-page-album-name">{{ photoAlbum.title }}</h6>
        <p  class="photoalbum-page-album-desc">{{ photoAlbum.description }}</p>
        <div class="photoalbum-page-album-btns d-flex flex-wrap justify-content-center mb-4">
            <button @click="showPhotoalbumEditModal"
                    class="btn plz-btn plz-btn-primary edit-album mx-3 mb-2">
                Редактировать альбом
            </button>
            <button class="btn plz-btn plz-btn-outline btn-registration delete-album mx-3 mt-0 mb-2"
                    @click="onShowDeletePhotoAlbumModal">
                Удалить альбом
            </button>
        </div>

        <PhotoalbumEditModal v-if="photoalbumEditModalShow"
                             @HidePhotoalbumEditModal="onHidePhotoalbumEditModal"
                             :photoAlbum="photoAlbum"/>

        <DeletePhotoAlbumModal v-if="deletePhotoAlbumModal.isVisible"
                               :id="deletePhotoAlbumModal.content.id"
                               :isShipped="isShippedDelete"
                               @onSuccessDelete="deletePhotoAlbum"
                               @onHide="onHideDeletePhotoAlbumModal"/>
    </div>
</template>

<script>
import PhotoalbumEditModal from './PhotoalbumEditModal.vue';
import AlertModal from '../AlertModal.vue';
import DeletePhotoAlbumModal from "./DeletePhotoAlbumModal.vue";

import PliziPhotoAlbum from "../../classes/PliziPhotoAlbum.js";

export default {
name: 'PhotoalbumEditBlock',
components: {
    PhotoalbumEditModal,
    AlertModal,
    DeletePhotoAlbumModal,
},
props: {
    photoAlbum: PliziPhotoAlbum,
},
data() {
    return {
        photoalbumEditModalShow: false,
        deletePhotoAlbumModal: {
            isVisible: false,
            content: {
                id: null,
            },
        },
        isSuccessDelete: false,
        isShippedDelete: false,
    }
},
methods: {
    showPhotoalbumEditModal() {
        this.photoalbumEditModalShow = true;
    },
    onHidePhotoalbumEditModal() {
        this.photoalbumEditModalShow = false;
    },
    onShowDeletePhotoAlbumModal() {
        this.deletePhotoAlbumModal.content.id = this.photoAlbum.id;
        this.deletePhotoAlbumModal.isVisible = true;
    },
    onHideDeletePhotoAlbumModal() {
        this.deletePhotoAlbumModal.isVisible = false;
        this.deletePhotoAlbumModal.content.id = null;
    },

    async deletePhotoAlbum() {
        this.isShippedDelete = true;
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$photoalbums.deleteAlbum(this.deletePhotoAlbumModal.content.id);
        } catch (e) {
            this.isShippedDelete = false;
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.$notify(`Альбом "${this.photoAlbum.title}" успешно удален.`);
            await this.$router.push({path: '/photoalbums-list'});
        }
    },
}
}
</script>
