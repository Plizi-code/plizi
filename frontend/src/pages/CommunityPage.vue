<template>
    <div class="row">
        <div class="col-12 col-md-1 ">
            <AccountToolbarLeft/>
        </div>

        <div class="col-sm-12 col-md-11 col-lg-9 col-xl-10">
            <CommunityHeader :community="communityData" :subscribeType="subscribeType"/>

            <div class="row">
                <div class="col-12 --col-sm-7 col-lg-8 col-xl-8">
                    <div v-if="isDataReady && (communityData.description || communityData.website)"
                         id="communityInfoBlock"
                         class="plz-community-info-block bg-white-br20 d-none d-lg-block py-3 px-4 mb-4 text-left">
                        <h4 class="plz-community-header-title">Информация</h4>

                        <p class="plz-community-info-desc">{{communityData.description}}</p>
                        <p><a :href="communityData.website" target="_blank" class="plz-community-info-desc">{{communityData.website}}</a></p>
                    </div>
                    <Spinner v-else-if="!isDataReady"/>

                    <CommunityEditor v-if="canPost"
                                     :community-id="communityData.id"
                                     :class="'mx-0 '"
                                     @addNewPost="addNewPost"/>

                    <CommunityPostsList
                        :community="communityData"
                        :has-access="hasAccess"
                        :is-started="isStarted"
                        :posts="posts"
                        :subscribe-type="subscribeType"
                        @openVideoModal="openVideoModal"/>

                    <template v-if="isStarted && hasAccess">
                        <div class="plz-post-item mb-4 bg-white-br20 p-4">
                            <div class="w-100 p-5 text-center mb-0">
                                <SmallSpinner/>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="col-12 --col-sm-5 col-lg-4">
<!--               TODO @tga мы этот блок можем удалить? -->
<!--                    <CommunityManagedActionBlock :community="communityData" v-if="isAuthor"></CommunityManagedActionBlock>-->
 <!--               TODO @tga мы этот блок можем удалить? -->
<!--                    @TGA отвечаю - пока не можем -->
                    <CommunityUserActionBlock v-if="!isAuthor" v-bind:community="communityData"/>

                    <CommunityFriendsInformer v-bind:community="communityData"/>

                    <CommunityShortMembers v-if="isDataReady" v-bind:community="communityData"/>

                    <CommunityVideoBlock v-if="isDataReady && hasAccess"
                             :key="'cv' + communityData.id"
                             :avatarMedium="avatarMedium"
                             :communityId="parseInt(id)"
                             @openVideoModal="openVideoModal"/>
                </div>
            </div>
        </div>

        <div class="col-sm-2  col-lg-2 col-xl-1 d-none d-lg-block">
            <FavoriteFriends :isNarrow="true"/>
        </div>

        <PostVideoModal v-if="postVideoModal.isVisible"
                        :videoLink="postVideoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>

    </div>
</template>

<script>

import PliziCommunity from '../classes/PliziCommunity.js';
import PliziPost from '../classes/PliziPost.js';

import CommunitiesSubscribeMixin from '../mixins/CommunitiesSubscribeMixin.js';
import CommunityPageMixin from '../mixins/CommunityPageMixin.js';
import HotCommunitiesMixin from '../mixins/HotCommunitiesMixin.js';
import LazyLoadPosts from '../mixins/LazyLoadPosts.js';
import PostViewMixin from '../mixins/PostViewMixin.js';

import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';

import CommunityEditor from '../common/Communities/CommunityEditor.vue';
import CommunityPostsList from '../components/Community/CommunityPostsList.vue';

export default {
name: 'CommunityPage',
props: {
    id : Number|String
},
mixins: [CommunitiesSubscribeMixin, LazyLoadPosts, PostViewMixin, HotCommunitiesMixin, CommunityPageMixin],
components : {
    CommunityPostsList,
    AccountToolbarLeft,
    FavoriteFriends,
    CommunityEditor,
},

data() {
    return {
        currentId: null,
        posts: [],
    }
},

watch: {
    $route: 'afterRouteUpdate' // при изменениях маршрута запрашиваем данные снова
},

computed: {
    isAuthor(){
        return this.communityData?.role === 'author';
    },
    subscribeType() {
        return this.getSubscribeType(this.communityData);
    },
    canPost() {
        /**
         * @todo check privacy
         */
        return this.communityData && this.communityData.role !== null;
    },
    avatarMedium() {
        return this.communityData?.avatar?.image.medium.path || this.communityData?.primaryImage;
    },
    hasAccess() {
        if (!this.communityData) {
            return true;
        }
        // Opened
        if (this.communityData?.privacy === 1) {
            return true;
        }
        return !!this.communityData?.role;
    }
},

methods: {
    afterRouteUpdate(ev){
        this.currentId = ev.params.id;
        this.posts = [];
        this.getCommunityInfo();
        window.scrollTo(0, 0);
    },

    addNewPost(post) {
        this.posts.unshift( new PliziPost( post ) );
    },

    /**
     * @TGA не понятно где используется и для чего
     */
    // ytInit(){
    //     let video = document.getElementsByClassName('video');
    //
    //     let videoWrap;
    //     for (let i = 0; i < video.length; i++) {
    //         videoWrap = video[i].getElementsByClassName('video_wrap');
    //         console.log(videoWrap);
    //     }
    // },

    async getPosts(limit = 50, offset = 0) {
        let response = null;
        this.isStarted = true;

        if (offset === 0) {
            this.posts = [];
        }

        try {
            response = await this.$root.$api.$communities.posts(this.currentId, limit, offset);
        } catch (e) {
            this.isStarted = false;
        }

        if (response !== null) {
            this.isStarted = false;
            response.map((post) => {
                this.posts.push(new PliziPost(post));
            });

            return response.length;
        }
    },

    onNeedAddCommunityToHot(evData){
        if (evData.communityId !== this.currentId)
            return;

        this.keyUpdater++;
        const comm = this.communityData || null;
        this.addCommunityToFavorites( evData.communityId, comm );

        if (this.$refs  &&  this.$refs.hotCommunitiesBlock) {
            this.$refs.hotCommunitiesBlock.$forceUpdate();
        }
    },

    setPageTitle(){
        if (this.communityData && this.communityData.name) {
            document.title = `Plizi: ${this.communityData.name}`;
        }
        else {
            document.title = `Plizi: Сообщества`;
        }
    },

    async getCommunityInfo() {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$communities.getCommunity(this.currentId);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (apiResponse) {
            this.communityData = new PliziCommunity(apiResponse);
            this.isDataReady = true;
            this.setPageTitle();

            setTimeout(() => {
                const getPosts = async () => {
                    await this.getPosts();
                };

                if (!this.hasAccess) {
                    this.noMore = true;
                    return;
                }
                getPosts();
                this.noMore = false;
            }, 100);
        }
    },
},

created(){
    this.currentId = this.id;

    this.$root.$on('NeedAddCommunityToHot', this.onNeedAddCommunityToHot);
},

async mounted() {
    await this.getCommunityInfo();
    window.scrollTo(0, 0);
},

/**
 * @TGA закоменченное ниже - ошибка но пусть пока будет
 */
//beforeRouteUpdate(to, from, next) {
//    this.communityData = null;
//    this.posts = null;
//    next();
//    this.id = to.params.id;
//    this.getCommunityInfo();
//    window.scrollTo(0, 0);
//},

}
</script>
