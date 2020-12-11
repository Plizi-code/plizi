<template>
    <div>
        <template v-if="photos.length || isPhotosDataReady">
            <div v-if="photos.length" id="profilePhotos" class="row bg-white-br20 p-4 mb-4">
                <div class="col-12 bg-white-br20 p-0">
                    <div class="w-100 d-flex flex-row justify-content-between align-items-center">
                        <div class="">
                            <template v-if="userImageNumber > 0">
                                <h6 class="profilePhotos-title my-0">Фотографии
                                    <span class="profilePhotos-desc" v-html="sBeaty(userImageNumber)"></span>
                                </h6>
                            </template>
                        </div>

                <div class="profilePhotos-desc d-none" >
                    <a href="#onmap">Показать на карте</a>
                </div>
            </div>
            <div class="d-flex flex-row plz-profile-photos-list pt-3">
                <vue-custom-scrollbar class="plz-latest-entries-list d-flex justify-content-between justify-content-sm-start pb-3"
                                      :settings="customScrollbarSettings">
                    <ProfileGallery type="album" v-if="photos.length > 0"
                                    :profilePhotos="profilePhotos"
                                    :images="photos"
                                    :isOwner="isOwner"></ProfileGallery>
                    <div v-else class="mx-auto">Нет фотографий</div>
                </vue-custom-scrollbar>
            </div>
        </div>
            </div>
        </template>
        <Spinner v-else></Spinner>
    </div>

</template>

<script>
    import ProfileGallery from '../common/Gallery/ProfileGallery.vue';
    import PliziUser from "../classes/PliziUser";
    import PliziAuthUser from "../classes/PliziAuthUser";
    import vueCustomScrollbar from "vue-custom-scrollbar";
    import Spinner from '../common/Spinner.vue';

export default {
name: 'ProfilePhotos',
    components: {
        ProfileGallery,
        vueCustomScrollbar,
        Spinner
    },
props: {
    photos: Array,
    isPhotosDataReady: Boolean,
    profileData: PliziUser | PliziAuthUser,
    isOwner: Boolean,
},
data () {
    return {
        profilePhotos: true,
        customScrollbarSettings: {
            maxScrollbarLength: 60,
            suppressScrollY: true, // rm scroll x
            wheelPropagation: false
        },
    }
},

computed : {
    userData() {
        return this.$root.$auth.user;
    },
    userImageNumber() {
        if (this.profileData) {
            return this.profileData.stats.imageCount;
        } else {
            return this.userData.stats.imageCount;
        }
    },
},
    methods: {
        sBeaty(param) {
            return this.$options.filters.statsBeauty(param);
        }
    },

}
</script>

