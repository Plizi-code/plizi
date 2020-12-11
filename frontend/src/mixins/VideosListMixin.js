import PliziVideo from "../classes/PliziVideo.js";

import SmallSpinner from '../common/SmallSpinner.vue';
import PostVideoModal from "../common/Post/PostVideoModal.vue";
import IconYoutube from "../icons/IconYoutube.vue";

const VideosListMixin = {
    components: {
        SmallSpinner,
        PostVideoModal,
        IconYoutube,
    },

    data() {
        return {
            noMore: false,
            enabledLoader: true,

            isDataReady: false,
            userVideoList: [],

            videoModal: {
                isVisible: false,
                content: {
                    videoLink: null,
                },
            },
        }
    },
    mounted() {
        window.addEventListener('scroll', this.onScrollYPage);
    },
    beforeRouteLeave(to, from, next) {
        window.removeEventListener('scroll', this.onScrollYPage);
        next();
    },

    methods: {
        async onScrollYPage() {
            if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                if (this.$route.name === 'userVideoList') {
                    await this.lazyLoad('userVideoList');
                }
            }
        },

        async loadUserVideoList(limit = 20, offset = 0) {
            this.enabledLoader = true;
            let apiResponse = null;

            if (offset === 0) {
                this.isDataReady = false;
            }

            try {
                apiResponse = await this.$root.$api.$video.getUserVideoById(this.userId, limit, offset);
            } catch (e) {
                this.enabledLoader = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            return this.processApiResponse(offset, apiResponse, 'userVideoList');
        },

        processApiResponse(offset, apiResponse, list) {
            this.enabledLoader = false;
            this.isDataReady = true;
            if (apiResponse) {
                if (offset === 0) {
                    this[list] = [];
                }
                apiResponse.list.map((pfItem) => {
                    this[list].push(new PliziVideo(pfItem));
                });

                return apiResponse.list.length;
            }

            return 0;
        },

        async lazyLoad(listName) {
            if (this.noMore || this.enabledLoader) return;

            this.enabledLoader = true;
            const oldSize = this[listName].length;
            let added;

            if (listName === 'userVideoList') {
                if (oldSize) {
                    added = await this.loadUserVideoList(20, oldSize + 1);
                }
            }

            if (added === 0) {
                this.noMore = true;
            }

            this.enabledLoader = false;
        },

        openVideoModal(link) {
            this.videoModal.isVisible = true;
            this.videoModal.content.videoLink = link;
        },
        hideVideoModal() {
            this.videoModal.isVisible = false;
        },
    },

};

export {VideosListMixin as default}
