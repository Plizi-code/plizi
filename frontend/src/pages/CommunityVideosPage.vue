<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft/>
            </div>

            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-10">
                <CommunityHeader :community="communityData" :subscribe-type="subscribeType"/>

                <div class="row">
                    <div class="col-12 --col-sm-7 col-lg-8 col-xl-8">

                        <div class="col-12 order-1 order-md-0 bg-white-br20 py-2">


                            <div class="card-body py-0" v-if="videos">
                                <div class="row">
                                    <div v-for="video in videos"
                                         :key="video.id"
                                         class="col-12 col-sm-6 col-xl-6 my-3">
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

                            <Spinner v-else/>
                        </div>
                    </div>

                    <div class="col-12 --col-sm-5 col-lg-4">
                        <!--               TODO @tga мы этот блок можем удалить? -->
                        <!--                    <CommunityManagedActionBlock :community="communityData" v-if="isAuthor"></CommunityManagedActionBlock>-->
                        <!--               TODO @tga мы этот блок можем удалить? -->
                        <!--                    @TGA отвечаю - пока не можем -->
                        <CommunityUserActionBlock v-if="!isAuthor" v-bind:community="communityData"/>

                        <CommunityFriendsInformer v-bind:community="communityData"/>

                        <CommunityShortMembers v-if="isDataReady" v-bind:community="communityData"/>
                    </div>
                </div>
            </div>
        </div>

        <PostVideoModal v-if="postVideoModal.isVisible"
                        :videoLink="postVideoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>

    </div>
</template>

<script>
    import PliziCommunity from "../classes/PliziCommunity.js";
    import PliziVideo from "../classes/PliziVideo.js";

    import CommunitiesSubscribeMixin from "../mixins/CommunitiesSubscribeMixin.js";
    import CommunityPageMixin from "../mixins/CommunityPageMixin.js";

    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';

    import FriendsAllList from '../components/FriendsAllList.vue';
    import CommunityMember from '../components/Community/CommunityMember.vue';

    import IconShare from '../icons/IconShare.vue';
    import IconYoutube from "../icons/IconYoutube.vue";

    export default {
        name: 'CommunityVideosPage',
        mixins: [CommunitiesSubscribeMixin, CommunityPageMixin],
        components: {
            IconYoutube,
            FriendsAllList,
            AccountToolbarLeft,
            CommunityMember,
            IconShare,
        },
        props: {
            id: Number | String,
        },
        data() {
            return {
                videos: [],
                isLoaded: true,
                role: null,

                lazyLoadStarted: false,
                noMore: false,
            }
        },
        computed: {
            isAuthor(){
                return this.communityData?.role === 'author';
            },
            subscribeType() {
                return this.getSubscribeType(this.communityData);
            },
            avatarMedium() {
                return this.communityData?.avatar?.image.medium.path || this.communityData?.primaryImage;
            },
            hasAccess() {
                // Opened
                if (this.communityData?.privacy === 1) {
                    return true;
                }
                return !!this.communityData?.role;
            }
        },
        methods: {
            async getCommunityInfo() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$communities.getCommunity(this.id);
                }
                catch (e){
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    this.communityData = new PliziCommunity(apiResponse);
                    this.isDataReady = true;
                    document.title = `Plizi: Видео от сообзества ${this.communityData?.name}`;

                    setTimeout(() => {
                        this.getVideoList();
                    }, 100);
                }
            },
            async getVideoList() {
                let apiResponse;
                this.isLoaded = false;

                try {
                    apiResponse = await this.$root.$api.$communities.videos(this.id, 30);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    apiResponse.list.map((video) => {
                        this.videos.push(new PliziVideo(video));
                    });
                    this.isLoaded = true;
                }
            },
            onScrollYPage() {
                if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                    this.membersLazyLoad(); // Дозагрузка!
                }
            },

            async membersLazyLoad() {
                if (this.lazyLoadStarted || this.noMore) {
                    return;
                }

                this.lazyLoadStarted = true;

                const added = 0;
                if (added === 0) {
                    this.noMore = true;
                }

                this.lazyLoadStarted = false;
                this.onScrollYPage();
            },
            openVideoModal(id) {
                this.postVideoModal.isVisible = true;
                this.postVideoModal.content.videoLink = id;
            },
            hideVideoModal(){
                this.postVideoModal.isVisible = false;
            },
        },
        mounted() {
            this.getCommunityInfo();
            window.scrollTo(0, 0);
            // window.addEventListener('scroll', this.onScrollYPage);
        }

    }
</script>
