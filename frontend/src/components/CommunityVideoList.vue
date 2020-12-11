<template>
    <div class="row">
        <div class="col-12">
            <div class="row plz-post-item mb-4 bg-white-br20 p-4" v-if="isDataReady">
                <h6 class="w-100 media m-0 py-4 px-4 border-bottom">Список видео</h6>
                <div v-if="userVideoList  &&  userVideoList.length > 0"
                    class="plizi-communities-list w-100 d-flex justify-content-between flex-wrap p-0">
                    <div class="card-body py-0" v-if="userVideoList">
                        <div class="row">
                            <div v-for="video in userVideoList"
                                 :key="'cvi_' + video.id"
                                 class="col-12 col-sm-6 col-xl-4 my-3">
                                <div v-if="video.isYoutubeLink" class="videos-item">
                                    <div class="video mb-2">
                                        <div class="video-wrap-pre">
                                            <img alt="image"
                                                 :src="`//img.youtube.com/vi/${video.youtubeId}/mqdefault.jpg`"
                                                 @click.stop="openVideoModal(video.link)">
                                        </div>
                                        <button type="button"
                                                aria-label="Запустить видео"
                                                class="video__button">
                                            <IconYoutube/>
                                        </button>
                                        <div class="video-time d-none">0:32</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="!enabledLoader" class="container px-2 ">
                    <div class=" bg-white-br20 p-3">
                        <div class="alert alert-info w-100 py-4 text-center m-0">
                            Пользователь еще не загрузил ни одного видео.
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="enabledLoader">
                <div class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <div class="w-100 p-5 text-center mb-0">
                        <SmallSpinner/>
                    </div>
                </div>
            </template>
        </div>

        <PostVideoModal v-if="videoModal.isVisible"
                        :videoLink="videoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>
    </div>
</template>

<script>
    import VideosListMixin from "../mixins/VideosListMixin.js";

    export default {
        name: "CommunityVideoList",
        mixins: [VideosListMixin],
        data() {
            return {
                userId: null,
            }
        },
        computed: {
            getUserId() {
                return this.$route.params.id;
            }
        },
        async mounted() {
            this.userId = this.$route.params.id;
            await this.loadUserVideoList();
        },
    }
</script>

<style scoped>

</style>
