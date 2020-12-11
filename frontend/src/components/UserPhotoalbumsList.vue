<template>
    <div class="row">
        <div class="col-12">
            <div class="row plz-post-item plz-box-shadow mb-4 bg-white-br20 p-4" v-if="isDataReady">
                <h6 class="w-100 media m-0 pb-4 px-4 border-bottom">Фотоальбомы</h6>
                <div v-if="photoAlbums  &&  photoAlbums.length > 0"
                     class="plizi-communities-list w-100 d-flex justify-content-between flex-wrap p-0">
                    <div class="card-body py-0" v-if="photoAlbums">
                        <div class="row">
                            <div v-for="(album) in photoAlbums"
                                 :key="album.id"
                                 class="col-12 col-sm-6 col-xl-3 my-3">
                                <UserPhotoalbumItem :album="album"
                                                :key="album.id"
                                                :userId="userId"></UserPhotoalbumItem>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="container px-2 ">
                    <div class=" bg-white-br20 p-3">
                        <div class="alert alert-info w-100 py-4 text-center m-0">
                            Пользователь еще не создал ни одного альбома.
                        </div>
                    </div>
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
    import UserPhotoalbumItem from "../components/UserPhotoalbumItem.vue";
    import SmallSpinner from "../common/SmallSpinner.vue";

    import PhotosListMixin from "../mixins/PhotosListMixin.js";

    export default {
        name: "UserPhotoalbumsList",
        components: {
            UserPhotoalbumItem,
            SmallSpinner
        },
        mixins: [PhotosListMixin],
        data() {
            return {
                userId: null,
            }
        },
        computed: {
            isOwner() {
                return this.userId === this.$root.$auth.user.id;
            }
        },
        async mounted() {
            this.userId = this.$route.params.id;
            await this.getPhotoAlbums(this.userId);
        },
    }
</script>
