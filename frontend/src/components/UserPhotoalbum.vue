<template>
    <div class="container-fluid pr-0 pl-md-0">
        <div class="row">
            <div class="row mr-0 ml-0 plz-box-shadow plz-post-item w-100 mb-4 bg-white-br20 p-4"
                 v-if="isAlbumDataReady">
                <div class="user-photoalbum-header d-flex flex-row w-100 media m-0 pb-4 px-4 border-bottom">
                    <a class="position-absolute cursor-pointer text-black-50" @click="$router.go(-1)">
                        <i class="fas fa-arrow-left mr-1"></i> К списку альбомов</a>
                    <h6 class="mx-auto my-auto">{{ photoAlbum.title }}</h6>
                </div>

                <GridGallery v-if="photoAlbum && photoAlbum.images && photoAlbum.images.length"
                             :isOwner="isOwner"
                             :images="photoAlbum.images"/>
                <div v-else class="alert alert-info bg-transparent border-0 text-secondary w-100 p-5 text-center mb-0">
                    Нет изображений.
                </div>
            </div>
            <template v-else>
                <div class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <div class="w-100 p-5 text-center mb-0">
                        <SmallSpinner/>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import SmallSpinner from "../common/SmallSpinner.vue";
    import GridGallery from "../common/Gallery/GridGallery.vue";

    import PhotosListMixin from "../mixins/PhotosListMixin.js";

    export default {
        name: "UserPhotoalbum",
        components: {
            SmallSpinner,
            GridGallery,
        },
        mixins: [PhotosListMixin],
        data() {
            return {
                userId: null,
                photoAlbumId: this.$route.params.albumId,
            }
        },
        computed: {
            isOwner() {
                return this.userId === this.$root.$auth.user.id;
            }
        },
        async mounted() {
            this.userId = this.$route.params.id;
            await this.getPhotoAlbum(this.photoAlbumId);

            this.$root.$on('onUpdate', this.onUpdatePhotoAlbum);
        },
    }
</script>



