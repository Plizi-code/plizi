<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>
            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3">
                <div class="row">
                    <div class="col-12">
                        <PhotoalbumsPageFilter/>
                    </div>
                    <div class="col-12">
                        <div class="videos-content w-100">
                            <div class="card mb-4">
                                <div class="card-body py-0">
                                    <div class="row">
                                        <template v-if="photoAlbums && photoAlbums.length">
                                            <div v-for="(album, index) in photoAlbums"
                                                 :key="index"
                                                 class="col-12 col-sm-6 col-xl-3 my-3">
                                                <PhotoalbumItem :album="album" :key="album.id"></PhotoalbumItem>
                                            </div>
                                        </template>

                                        <div v-else class="alert alert-info bg-transparent border-0 text-secondary w-100 p-5 text-center mb-0">
                                            Нет альбомов.
                                        </div>
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
    import SmallSpinner from "../common/SmallSpinner.vue";

    import PliziPhotoAlbum from "../classes/PliziPhotoAlbum.js";

    export default {
        name: "PhotoalbumsListPage",
        components: {
            AccountToolbarLeft,
            FavoriteFriends,
            PhotoalbumsPageFilter,
            PhotoalbumsPageModal,
            PhotoalbumItem,
            SmallSpinner
        },
        data() {
            return {
                photoAlbums: null,
                filterMode: 'my'
            }
        },
        methods: {
            onAddPhotoAlbum(photoAlbum) {
                this.photoAlbums.unshift(new PliziPhotoAlbum(photoAlbum));
            },

            async getPhotoAlbums() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$photoalbums.list();
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    this.photoAlbums = apiResponse.map((photoAlbum) => {
                        return new PliziPhotoAlbum(photoAlbum);
                    });
                }
            }
        },
        async mounted() {
            await this.getPhotoAlbums();

            this.$root.$on('onAddPhotoAlbum', this.onAddPhotoAlbum);
        },
    }
</script>

