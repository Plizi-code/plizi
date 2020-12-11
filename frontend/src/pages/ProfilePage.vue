<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1  px-0 px-md-3">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 pr-0 px-0 px-md-3"
                 :class="calcCentralBlockClass()"
                 v-bind:key="`CentralColumn-`+shortFriendsUpdater">

                <div class="container">
                    <ProfileHeader
                        refs="profileHeader"
                        v-bind:userData="userData(profileHeaderKeyUpdater)"
                        v-bind:isOwner="true"
                        v-bind:key="'profileHeader-'+profileHeaderKeyUpdater"></ProfileHeader>

                    <template v-if="userPhotos.length > 0">
                    <ProfilePhotos v-if="isPhotosDataReady" v-bind:photos="userPhotos" v-bind:isOwner="true"></ProfilePhotos>
                        <SmallSpinner v-else></SmallSpinner>
                    </template>

                    <WhatsNewBlock @addNewPost="addNewPost"></WhatsNewBlock>

                    <ProfileFilter v-if="posts && posts.length > 1"
                                   @wallPostsSelect="wallPostsSelectHandler"></ProfileFilter>

                    <template v-if="posts && posts.length > 0">
                        <Post v-for="postItem in filteredPosts"
                              :key="postItem.id"
                              :post="postItem"
                              @onDeletePost="onDeletePost"
                              @onRestorePost="onRestorePost"
                              @onEditPost="onEditPost"
                              @openVideoModal="openVideoModal"
                              @onShowUsersLikes="openLikeModal"
                              @onShare="onSharePost">
                        </Post>
                    </template>

                    <div v-else-if="!isStarted"  class="row plz-post-item mb-4 bg-white-br20 p-4">
                        <div class="alert alert-info w-100 p-5 text-center mb-0">
                            Извините, но сейчас нечего показывать.
                        </div>
                    </div>

                    <template v-if="isStarted">
                        <div class="row plz-post-item mb-4 bg-white-br20 p-4">
                            <div class="w-100 text-center mb-0">
                                <SmallSpinner/>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div v-if="$root.$auth.frm.size > 0" class="col-sm-3 col-md-3 col-lg-3 col-xl-3 pr-0 d-none d-xl-block"
                 v-bind:key="`RightColumn-`+shortFriendsUpdater">

                <FavoriteFriends ref="favoriteFriends" :isNarrow="false"></FavoriteFriends>
                <ShortFriends
                    ref="shortFriends"
                    v-bind:key="`shortFriendsBlock-`+shortFriendsUpdater"
                    v-bind:friends="getAllFriends()">
                    </ShortFriends>
            </div>

            <PostEditModal v-if="postEditModal.isVisible"
                           v-bind:post="postForEdit"
                           @hidePostEditModal="hidePostEditModal"></PostEditModal>

            <PostVideoModal v-if="postVideoModal.isVisible"
                            v-bind:videoLink="postVideoModal.content.videoLink"
                            @hideVideoModal="hideVideoModal"></PostVideoModal>

            <PostLikeModal v-if="postLikeModal.isVisible"
                           v-bind:postId="postLikeModal.content.postId"
                           @hideLikeModal="hideLikeModal"></PostLikeModal>

            <PostRepostModal v-if="postRepostModal.isVisible"
                           v-bind:user="postRepostModal.content.postForRepost.author"
                           v-bind:post="postRepostModal.content.postForRepost"
                           @hidePostRepostModal="hidePostRepostModal"></PostRepostModal>
        </div>
    </div>
</template>

<script>
import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';

import SmallSpinner from '../common/SmallSpinner.vue';
import ShortFriends from '../common/ShortFriends.vue';
import Post from '../common/Post/Post.vue';
import WhatsNewBlock from '../common/WhatsNewBlock.vue';

import ProfileHeader from '../components/ProfileHeader.vue';
import ProfilePhotos from '../components/ProfilePhotos.vue';
import ProfileFilter from '../components/ProfileFilter.vue';
import PostEditModal from '../common/Post/PostEditModal.vue';
import PostVideoModal from '../common/Post/PostVideoModal.vue';
import PostRepostModal from '../common/Post/PostRepostModal.vue';

import PostLikeModal from '../common/Post/PostLikeModal.vue';

import LazyLoadPosts from '../mixins/LazyLoadPosts.js';
import PhotosListMixin from '../mixins/PhotosListMixin.js';

import PliziPost from '../classes/PliziPost.js';

export default {
name: 'ProfilePage',
components: {
    AccountToolbarLeft, FavoriteFriends, ShortFriends,
    ProfileHeader, ProfilePhotos, WhatsNewBlock, ProfileFilter, Post,
    PostEditModal,
    PostVideoModal,
    PostLikeModal,
    PostRepostModal,
    SmallSpinner,
},

mixins: [LazyLoadPosts, PhotosListMixin],

data() {
    return {
        posts: [],
        filterMode: 'all',

        userPhotos: [],
        isPhotosDataReady: false,
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
        postLikeModal: {
            isVisible: false,
            content: {
                postId: null,
            },
        },
        postRepostModal: {
            isVisible: false,
            content: {
                postForRepost: null,
            },
        },
        profileHeaderKeyUpdater: 0
    }
},

computed : {
    shortFriendsUpdater(){
        return this.$root.$friendsKeyUpdater+'-'+this.$root.$favoritesKeyUpdater;
    },

    /**
     * @returns {PliziPost[]}
     */
    filteredPosts(){
        switch ( this.filterMode ){
            case 'my':
                return this.posts.filter( post => !post.sharedFrom );

            case 'archive':
                return this.posts.filter( post => post.isArchivePost );
        }

        return this.posts;
    }
},

methods : {
    userData(){
        return this.$root.$auth.user;
    },

    calcCentralBlockClass(){
        return {
            'col-xl-8 pr-xl-3' : (this.$root.$auth.frm.size > 0),
            'col-xl-11' : (this.$root.$auth.frm.size === 0),
        };
    },

    getAllFriends(){
        return this.$root.$auth.frm.asArray();
    },

    wallPostsSelectHandler( evData ){
        this.filterMode = evData.wMode;
    },

    openVideoModal( evData ){
        if ( evData.videoLink ){
            this.postVideoModal.isVisible = true;
            this.postVideoModal.content.videoLink = evData.videoLink;
        }
    },

    hideVideoModal() {
        this.postVideoModal.isVisible = false;
    },

    addNewPost(post) {
        this.posts.unshift(new PliziPost(post));
    },

    startTimer(post){
        setTimeout( () => {
            let postIndex = this.posts.findIndex(item => item.id === post.id);

            this.posts.splice( postIndex, 1 );
        }, 5000 );
    },

    onEditPost( post ){
        this.postEditModal.isVisible = true;
        this.postForEdit = post;
    },

    hidePostEditModal(){
        this.postEditModal.isVisible = false;
        this.postForEdit = null;
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

        try{
            response = await this.$root.$api.$post.getPosts(limit, offset);
        } catch (e){

            this.isStarted = false;
        }

        if ( response !== null ){
            this.isStarted = false;
            response.map((post) => {
                this.posts.push(new PliziPost(post));
            });

            return response.length;
        }
    },

    async onDeletePost(id) {
        let response;

        try{
            response = await this.$root.$api.$post.deletePost( id );
        } catch (e){
            console.warn( e.detailMessage );
        }

        if ( response ){
            const post = this.posts.find(post => post.id === id);

            post.deleted = true;
            this.startTimer(post);
        }
    },

    async onRestorePost( id ) {
        let response;

        try{
            response = await this.$root.$api.$post.restorePost( id );
        } catch (e){
            console.warn( e.detailMessage );
        }

        if ( response ){
            const post = this.posts.find( ( post ) => {
                return post.id === id;
            } );

            post.deleted = false;
        }
    },
},

created(){
    this.$root.$on( 'UserIsUpdated', ()=>{
        if (this.$refs  &&  this.$refs.shortFriends){
            this.$refs.shortFriends.$forceUpdate();
        }
    });
},

async mounted() {
    await this.getUserPhotos(this.$root.$auth.user.id);
    await this.getPosts();
},
}
</script>

