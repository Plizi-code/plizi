<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-lg-9 col-xl-10  px-0 px-md-3">
                <div class="container">
                    <WhatsNewBlock @addNewPost="addNewPost"></WhatsNewBlock>

                    <div class="row mb-4 pt-0">
                        <PostFilter
                            :filter="filter"
                            @partsChange="partsChange"
                            @likedClick="likedClick"
                            @lastSearchChange="lastSearchChange"/>
                        <PostInterest
                            :filter="filter"
                            @interestSwitch="interestSwitch"/>
                    </div>

                    <div v-if="filteredPosts && filteredPosts.length > 0">
                        <Post v-for="postData in filteredPosts"
                              v-waypoint="{ active: true, callback: ({ el, going, direction })=>{ postIsRead(postData, going, direction); }, options: { threshold: [0.5] } }"
                              v-bind:post="postData"
                              v-bind:key="'post-'+postData.id"
                              @onEditPost="onEditPost"
                              @onDeletePost="onDeletePost"
                              @onRestorePost="onRestorePost"
                              @openVideoModal="openVideoModal"
                              @onShare="onSharePost"
                              @onShowUsersLikes="openLikeModal"></Post>
                    </div>

                    <div v-else-if="!isStarted"  class="row plz-post-item mb-4 bg-white-br20 p-4">
                        <div class="alert alert-info w-100 p-5 text-center mb-0">
                            Извините, но сейчас нечего показывать.
                        </div>
                    </div>

                    <template v-if="isStarted">
                        <div class="row plz-post-item mb-4 bg-white-br20 p-4">
                            <div class="w-100 p-5 text-center mb-0">
                                <SmallSpinner/>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>

        <PostEditModal v-if="postEditModal.isVisible"
                       :post="postForEdit"
                       @hidePostEditModal="hidePostEditModal"/>

        <PostVideoModal v-if="postVideoModal.isVisible"
                        :videoLink="postVideoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>

        <PostRepostModal v-if="postRepostModal.isVisible"
                         v-bind:user="postRepostModal.content.postForRepost.author"
                         v-bind:post="postRepostModal.content.postForRepost"
                         @hidePostRepostModal="hidePostRepostModal"></PostRepostModal>

        <PostLikeModal v-if="postLikeModal.isVisible"
                       :postId="postLikeModal.content.postId"
                       @hideLikeModal="hideLikeModal"/>
    </div>
</template>

<script>
import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import SmallSpinner from '../common/SmallSpinner.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';

import WhatsNewBlock from '../common/WhatsNewBlock.vue';

import Post from '../common/Post/Post.vue';
import PostFilter from '../common/Post/PostFilter.vue';
import PostInterest from '../common/Post/PostInterest.vue';
import PostEditModal from '../common/Post/PostEditModal.vue';
import PostVideoModal from '../common/Post/PostVideoModal.vue';
import PostRepostModal from '../common/Post/PostRepostModal.vue';
import PostLikeModal from '../common/Post/PostLikeModal.vue';

import LazyLoadPosts from '../mixins/LazyLoadPosts.js';
import PostViewMixin from '../mixins/PostViewMixin.js';

import PliziPost from '../classes/PliziPost.js';

export default {
name: 'NewsPage',
components: {
    PostInterest,
    PostFilter,
    AccountToolbarLeft,
    FavoriteFriends,
    WhatsNewBlock,
    Post,
    PostEditModal,
    PostVideoModal,
    PostRepostModal,
    SmallSpinner,
    PostLikeModal,
},
mixins: [LazyLoadPosts, PostViewMixin],

data() {
    return {
        posts: [],
        filteredPosts: [],
        postEditModal: {
            isVisible: false,
        },
        postForEdit: null,
        postVideoModal: {
            isVisible: false,
            content: {
                videoLink: null,
            },
        },
        postRepostModal: {
            isVisible: false,
            content: {
                postForRepost: null,
            },
        },
        postLikeModal: {
            isVisible: false,
            content: {
                postId: null,
            },
        },
        lastSearch: '',
        filter: {
            interest: false,
            liked: false,
            parts: [],
        },
    }
},

methods: {

    filterPost() {
        this.filteredPosts = [...this.posts];
        if (this.filter.interest) {
            this.filteredPosts.sort((a, b) => {
                return (b.commentsCount + b.likes) - (a.commentsCount + a.likes);
            });
        }
        if (this.filter.liked) {
            this.filteredPosts = this.filteredPosts.filter((post) => {
                return post.alreadyLiked;
            });
        }
    },
    addNewPost(post) {
        this.posts.unshift(new PliziPost(post));
        this.filterPost();
    },
    onEditPost(post){
        this.postEditModal.isVisible = true;
        this.postForEdit = post;
    },
    hidePostEditModal(){
        this.postEditModal.isVisible = false;
        this.postForEdit = null;
    },
    openVideoModal(evData){
        if ( evData.videoLink ){
            this.postVideoModal.isVisible = true;
            this.postVideoModal.content.videoLink = evData.videoLink;
        }
    },
    hideVideoModal(){
        this.postVideoModal.isVisible = false;
    },
    startTimer(postIndex) {
        setTimeout(() => {
            this.posts.splice(postIndex, 1);
            this.filterPost();
        }, 5000);
    },
    onSharePost(post) {
        this.postRepostModal.isVisible = true;
        this.postRepostModal.content.postForRepost = post;
    },
    hidePostRepostModal() {
        this.postRepostModal.isVisible = false;
        this.postRepostModal.content.postForRepost = null;
    },
    openLikeModal(postId) {
        this.postLikeModal.isVisible = true;
        this.postLikeModal.content.postId = postId;
    },

    hideLikeModal() {
        this.postLikeModal.isVisible = false;
        this.postLikeModal.content.postId = null;
    },

    async getPosts(limit = 50, offset = 0) {
        let response = null;
        this.isStarted = true;

        try {
            response = await this.$root.$api.$post.getNews(limit, offset, this.startSearch, this.filter.parts);
        } catch (e) {
            this.isStarted = false;
            console.warn(e.message);
        }

        if (response !== null) {
            this.isStarted = false;
            if (offset === 0) {
                this.posts = [];
            }
            response.map((post) => {
                this.posts.push(new PliziPost(post));
            });
            this.filterPost();

            return response.length;
        }
    },
    lastSearchChange(sText) {
        this.startSearch = sText;
        this.getPosts();
    },
    async onDeletePost(id) {
        let response;

        try{
            response = await this.$root.$api.$post.deletePost( id );
        } catch (e){
            console.warn( e.detailMessage );
        }

        if (response){
            const postIndex = this.posts.findIndex((post) => {
                return post.id === id;
            });
            let post = this.posts[postIndex].deleted = true;

            this.startTimer(postIndex);
        }
    },
    async onRestorePost(id) {
        let response;

        try{
            response = await this.$root.$api.$post.restorePost(id);
        } catch (e){
            console.warn(e.detailMessage);
        }

        if (response){
            const post = this.posts.find((post) => {
                return post.id === id;
            });

            post.deleted = false;
            this.filterPost();
        }
    },
    interestSwitch(state) {
        this.filter.interest = state;
        this.filterPost();
    },
    likedClick(state) {
        this.filter.liked = state;
        this.filterPost();
    },
    partsChange(state) {
        this.filter.parts = state;
        this.getPosts();
    },
},

async mounted() {
    await this.getPosts();
},
}
</script>

