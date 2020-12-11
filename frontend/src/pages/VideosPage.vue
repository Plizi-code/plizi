<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>
            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3">
                <div class="row">
                    <div class="col-12">
                        <VideosPageFilter @wallPostsSelect="wallPostsSelectHandler"/>
                    </div>
                    <div class="col-12">
                        <div class="videos-content w-100 pt-4 px-3 mb-4 bg-white-br20">
                            <template v-if="filterMode === 'my'">

                                        <div class="row">
                                            <template v-if="userVideos && userVideos.length">
                                                <div v-for="(video, index) in userVideos"
                                                     :key="index"
                                                     class="col-12 col-sm-6 col-xl-3 my-3">
                                                    <div v-if="video.isYoutubeLink" class="videos-item">
                                                        <div class="video mb-2">
                                                            <div class="video-wrap-pre">
                                                                <img alt="image"
                                                                     :src="`//img.youtube.com/vi/${video.youtubeId}/mqdefault.jpg`"
                                                                     @click.stop="openVideoModal(video.link)" />
                                                            </div>
                                                            <button type="button"
                                                                    aria-label="Запустить видео"
                                                                    class="video__button">
                                                                <IconYoutube/>
                                                            </button>
                                                            <button type="button"
                                                                    @click.prevent="onDelete(video.id)"
                                                                    class="btn btn-close btn-link border-0 border-danger bg-danger text-white rounded-circle delete-button"
                                                                    aria-label="Удалить видео">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                            <div class="video-time d-none">0:32</div>
                                                        </div>
                                                        <a href="/user-1" class="video-desc d-none mb-0">Sunrise</a>
                                                    </div>
                                                </div>
                                            </template>

                                            <div v-else class="alert alert-info bg-transparent border-0 text-secondary w-100 p-5 text-center mb-0">
                                                Нет видео.
                                            </div>
                                        </div>

                            </template>
                            <template v-else>
                                <div class="card mb-4">
                                    <div class="card-header pb-0 bg-white border-bottom-0">Мои видео</div>
                                    <div class="card-body py-0 pr-0">
                                    <div class="ml-n3 clearfix">
                                        <div class="col-12 col-xl-6 my-3 float-left ">
                                            <div class="videos-item videos-item-wide">
                                                <div class="video mb-2">
                                                    <div class="video-wrap-pre">
                                                        <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                    </div>
                                                    <button type="button" aria-label="Запустить видео" class="video__button">
                                                        <IconYoutube/>
                                                    </button>
                                                    <div class="video-time">0:32</div>
                                                </div>
                                                <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-3 my-3 float-left ">
                                            <div class="videos-item ">
                                                <div class="video mb-2">
                                                    <div class="video-wrap-pre">
                                                        <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                    </div>
                                                    <button type="button" aria-label="Запустить видео" class="video__button">
                                                        <IconPlayVideo/>
                                                    </button>
                                                    <div class="video-time">0:32</div>
                                                </div>
                                                <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-3 my-3 float-left ">
                                            <div class="videos-item ">
                                                <div class="video mb-2">
                                                    <div class="video-wrap-pre">
                                                        <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                    </div>
                                                    <button type="button" aria-label="Запустить видео" class="video__button">
                                                        <IconYoutube/>
                                                    </button>
                                                    <div class="video-time">0:32</div>
                                                </div>
                                                <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-3 my-3 float-left ">
                                            <div class="videos-item ">
                                                <div class="video mb-2">
                                                    <div class="video-wrap-pre">
                                                        <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                    </div>
                                                    <button type="button" aria-label="Запустить видео" class="video__button">
                                                        <IconPlayVideo/>
                                                    </button>
                                                    <div class="video-time">0:32</div>
                                                </div>
                                                <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-3 my-3 float-left ">
                                            <div class="videos-item ">
                                                <div class="video mb-2">
                                                    <div class="video-wrap-pre">
                                                        <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                    </div>
                                                    <button type="button" aria-label="Запустить видео" class="video__button">
                                                        <IconPlayVideo/>
                                                    </button>
                                                    <div class="video-time">0:32</div>
                                                </div>
                                                <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="card mb-4">
                                    <div class="card-header pb-0 bg-white border-bottom-0">Прямые трансляции</div>
                                    <div class="card-body py-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4">
                                    <div class="card-header pb-0 bg-white border-bottom-0">Рекомендуемое видео</div>
                                    <div class="card-body py-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4">
                                    <div class="card-header pb-0 bg-white border-bottom-0">Лучшее за неделю</div>
                                    <div class="card-body py-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconYoutube/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-xl-3 my-3">
                                                <div class="videos-item ">
                                                    <div class="video mb-2">
                                                        <div class="video-wrap-pre">
                                                            <img alt="image" src="https://cdn.pixabay.com/photo/2019/06/27/06/49/plane-4301615_960_720.png">
                                                        </div>
                                                        <button type="button" aria-label="Запустить видео" class="video__button">
                                                            <IconPlayVideo/>
                                                        </button>
                                                        <div class="video-time">0:32</div>
                                                    </div>
                                                    <a href="/user-1" class="video-desc mb-0">Sunrise </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-1 d-none d-lg-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>

        <VideoPageModal v-if="videoModal.isVisible"
                        :videoLink="videoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>

        <DeleteVideoModal v-if="deleteVideoModal.isVisible"
                          :id="deleteVideoModal.content.id"
                          @onHideDeleteVideoModal="hideDeleteVideoModal"
                          @onSuccessDeleteVideoModal="onSuccessDeleteVideoModal"/>
    </div>
</template>

<script>
import IconYoutube from "../icons/IconYoutube.vue";
import IconPlayVideo from "../icons/IconPlayVideo.vue";
import IconDelete from "../icons/IconDelete.vue";

import AccountToolbarLeft from "../common/AccountToolbarLeft.vue";
import FavoriteFriends from "../common/FavoriteFriends.vue";
import VideosPageFilter from "../components/VideosPage/VideosPageFilter.vue";
import VideoPageModal from "../components/VideosPage/VideoPageModal.vue";
import DeleteVideoModal from "../components/VideosPage/DeleteVideoModal.vue";

import LinkMixin from "../mixins/LinkMixin.js";
import PliziVideo from '../classes/PliziVideo.js';

export default {
name: "VideosPage",
components: {
    IconPlayVideo,
    IconYoutube,
    AccountToolbarLeft,
    FavoriteFriends,
    VideosPageFilter,
    VideoPageModal,
    IconDelete,
    DeleteVideoModal,
},
mixins: [LinkMixin],
data() {
    return {
        filterMode: 'my',
        userVideos: [],
        videoModal: {
            isVisible: false,
            content: {
                videoLink: null,
            },
        },
        deleteVideoModal: {
            isVisible: false,
            content: {
                id: null,
            },
        },
        isSuccess: false,
        isDeleteRequest: false,
    }
},

methods: {
    wallPostsSelectHandler(evData) {
        this.filterMode = evData.wMode;
    },
    openVideoModal(id) {
        this.videoModal.isVisible = true;
        this.videoModal.content.videoLink = id;
    },
    hideVideoModal(){
        this.videoModal.isVisible = false;
    },
    onAddVideo(video) {
        this.userVideos.unshift(new PliziVideo(video));
    },
    openDeleteVideoModal(id) {
        this.deleteVideoModal.isVisible = true;
        this.deleteVideoModal.content.id = id;
    },
    hideDeleteVideoModal() {
        this.deleteVideoModal.isVisible = false;
    },
    onDelete(id) {
        this.openDeleteVideoModal(id);
    },

    async onSuccessDeleteVideoModal() {
        if (this.isDeleteRequest) {
            return;
        }
        this.isDeleteRequest = true;
        let response;

        try {
            response = await this.$root.$api.$video.deleteVideo(this.deleteVideoModal.content.id);
        } catch (e) {
            console.log(e.detailMessage);
        }

        if (response) {
            let userVideoIndex = this.userVideos.findIndex(userVideo => userVideo.id === this.deleteVideoModal.content.id);
            this.userVideos.splice(userVideoIndex, 1);

            this.hideDeleteVideoModal();
            this.isDeleteRequest = false;
            this.$notify('Видео успешно удалено.');
            this.$root.$auth.videosDecrease();
        }
        else {
            this.isDeleteRequest = false;
        }
    },

    async getUserVideo() {
        let response;

        try {
            response = await this.$root.$api.$video.getUserVideo();
        }
        catch (e) {
            console.log(e.detailMessage);
        }

        if (response) {
            response.map((video) => {
                this.userVideos.push(new PliziVideo(video));
            });

            this.$root.$auth.setVideosNumber( this.userVideos.length );
        }
    },
},

async mounted() {
    await this.getUserVideo();

    this.$root.$on('onAddVideo', this.onAddVideo);
},
}
</script>

