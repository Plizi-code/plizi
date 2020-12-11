<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>
            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3">
                <div class="row">
                    <div class="col-12">
                        <PhotoalbumsPageFilter :photoAlbum="photoAlbum"
                                               @addNewImages="addNewImages"
                                               @uploadingImage="uploadingImage"/>
                    </div>
                    <div class="col-12">
                        <div class="photoalbum-images-content  pt-4 mb-4 bg-white-br20">
                            <div class="col-12">
                                <div class="photo-album-description-block">
                                    <PhotoalbumEditBlock :photoAlbum="photoAlbum"/>
                                </div>
                                <div class="row">
                                    <GridGallery v-if="photoAlbum && photoAlbum.images && photoAlbum.images.length"
                                                 type="album"
                                                 :images="photoAlbum.images"
                                                 @onDelete="onDeleteImage"
                                                 :isOwner="true"/>
                                    <div v-else class="alert alert-info bg-transparent border-0 text-secondary w-100 p-5 text-center mb-0">
                                        Нет изображений.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-1 d-none d-lg-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountToolbarLeft from "../common/AccountToolbarLeft.vue";
    import FavoriteFriends from "../common/FavoriteFriends.vue";
    import PhotoalbumsPageFilter from "../components/PhotoalbumsPage/PhotoalbumsPageFilter.vue";
    import PhotoalbumsPageModal from "../components/PhotoalbumsPage/PhotoalbumsPageModal.vue";
    import PhotoalbumItem from "../components/PhotoalbumsPage/PhotoalbumItem.vue";
    import PhotoalbumEditBlock from "../components/PhotoalbumsPage/PhotoalbumEditBlock.vue";
    import IconDelete from "../icons/IconDelete.vue";
    import SmallSpinner from "../common/SmallSpinner.vue";
    import GridGallery from "../common/Gallery/GridGallery.vue";

    import PliziPhotoAlbum from "../classes/PliziPhotoAlbum.js";
    import PliziAttachment from "../classes/PliziAttachment.js";

    export default {
        name: "PhotoalbumPage",
        components: {
            AccountToolbarLeft,
            FavoriteFriends,
            PhotoalbumsPageFilter,
            PhotoalbumsPageModal,
            PhotoalbumItem,
            PhotoalbumEditBlock,
            IconDelete,
            SmallSpinner,
            GridGallery,
        },
        data() {
            return {
                photoAlbumId: this.$route.params.id,
                photoAlbum: null,
            }
        },
        methods: {
            onUpdatePhotoAlbum({ title, description }) {
                this.photoAlbum.title = title;
                this.photoAlbum.description = description;
            },
            addNewImages(image) {
                if (!this.photoAlbum.images) {
                    this.photoAlbum.images = [];
                }

                let loadImageIndex = this.photoAlbum.images.findIndex(loadImage => (loadImage.originalName === image.originalName) && loadImage.isBlob);
                Vue.set(this.photoAlbum.images, loadImageIndex, new PliziAttachment(image));

                // TODO: @YZ сделать по нормальному после MVP
                let lsUser = JSON.parse(localStorage.getItem('pliziUser'));

                if (!lsUser.data.stats.imageCount) {
                    lsUser.data.stats.imageCount = 1;
                } else {
                    lsUser.data.stats.imageCount++;
                }

                localStorage.setItem('pliziUser', JSON.stringify(lsUser));
            },
            uploadingImage(image) {
                if (!this.photoAlbum.images) {
                    this.photoAlbum.images = []
                }

                this.photoAlbum.images.unshift(image);
            },

            async onDeleteImage(id) {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$photoalbums.deleteImage(id);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    let deletedImageIndex = this.photoAlbum.images.findIndex(image => image.id === id);
                    this.photoAlbum.images.splice(deletedImageIndex, 1);

                    // TODO: @YZ сделать по нормальному после MVP
                    let lsUser = JSON.parse(localStorage.getItem('pliziUser'));

                    if (!lsUser.data.stats.imageCount) {
                        lsUser.data.stats.imageCount = 0;
                    } else {
                        lsUser.data.stats.imageCount--;
                    }

                    localStorage.setItem('pliziUser', JSON.stringify(lsUser));
                }
            },
            async getPhotoAlbum() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$photoalbums.getPhotoAlbum(this.photoAlbumId);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    this.photoAlbum = new PliziPhotoAlbum(apiResponse);
                }
            }
        },
        async mounted() {
            await this.getPhotoAlbum();

            this.$root.$on('onUpdate', this.onUpdatePhotoAlbum);
        },
    }
</script>



