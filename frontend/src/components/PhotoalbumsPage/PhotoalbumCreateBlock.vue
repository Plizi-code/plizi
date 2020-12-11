<template>
    <div id="createCommunityBlock" class="mx-auto mr-sm-0">
        <button type="button"
                @click="showPhotoalbumCreateModal"
                class="btn plz-btn plz-btn-primary p-0 ">
            Создать альбом
        </button>
        <PhotoalbumCreateModal v-if="photoalbumCreateModalShow"
                 @HidePhotoalbumCreateModal="onHidePhotoalbumCreateModal"
        >
        </PhotoalbumCreateModal>
    </div>
</template>

<script>
import PhotoalbumCreateModal from './PhotoalbumCreateModal';

export default {
name: 'PhotoalbumCreateBlock',
components: {
    PhotoalbumCreateModal
},
data(){
    return {
        photoalbumCreateModalShow : false
    }
},

methods: {
    showPhotoalbumCreateModal(){
        this.photoalbumCreateModalShow = true;
    },

    onHidePhotoalbumCreateModal(){
        this.photoalbumCreateModalShow = false;
    }
}

}
</script>
