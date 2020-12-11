<template>
    <div>
        <div v-if="posts && posts.length > 0" id="communityPostsBlock" class="pb-5 mb-4 --text-center">
            <Post v-for="postItem in posts"
                  v-waypoint="{ active: true, callback: ({ el, going, direction })=>{ postIsRead(postItem, going, direction); }, options: { threshold: [0.5] } }"
                  :key="postItem.id"
                  :post="postItem"
                  :isCommunity="true"
                  :class="'mx-0'"
                  :isAdmin="['admin', 'author'].includes(community.role)"
                  @onShare="onSharePost"
                  @onDeletePost="onDeletePost"
                  @onRestorePost="onRestorePost"
                  @onEditPost="onEditPost"
                  @openVideoModal="openVideoModal"
                  @onShowUsersLikes="openLikeModal"/>
        </div>

        <div v-else-if="!hasAccess" class="plz-post-item mb-4 bg-white-br20 p-4">
            <div class="alert alert-info w-100 p-5 text-center mb-0">
                У Вас нет доступа.
                <p v-if="subscribeType === 'request'">
                    Отправьте
                    <a href="#" @click.stop="sendRequest(community)">запрос</a>
                    на вступление в сообщество
                </p>
            </div>
        </div>

        <div v-else-if="!isStarted" class="row plz-post-item mb-4 bg-white-br20 p-4">
            <div class="alert alert-info w-100 p-5 text-center mb-0">
                Извините, но сейчас нечего показывать.
            </div>
        </div>

        <PostEditModal v-if="postEditModal.isVisible"
                       :post="postForEdit"
                       @hidePostEditModal="hidePostEditModal"/>

        <PostRepostModal v-if="postRepostModal.isVisible"
                         :community="community"
                         :post="postForRepost"
                         @hidePostRepostModal="hidePostRepostModal"/>

        <PostLikeModal v-if="postLikeModal.isVisible"
                       :postId="postLikeModal.content.postId"
                       @hideLikeModal="hideLikeModal"/>
    </div>
</template>

<script>
import Post from '../../common/Post/Post.vue';
import PostEditModal from '../../common/Post/PostEditModal.vue';
import PostLikeModal from '../../common/Post/PostLikeModal.vue';
import PostRepostModal from '../../common/Post/PostRepostModal.vue';
import PostVideoModal from '../../common/Post/PostVideoModal.vue';

import CommunitiesSubscribeMixin from '../../mixins/CommunitiesSubscribeMixin.js';
import PostViewMixin from '../../mixins/PostViewMixin.js';

import communityUtils from '../../utils/CommunityUtils.js';
import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name: "CommunityPostsList",
components: {PostVideoModal, PostLikeModal, PostRepostModal, PostEditModal, Post},
mixins: [CommunitiesSubscribeMixin, PostViewMixin],

props: {
    community: PliziCommunity,
    posts: Array,
    hasAccess: Boolean,
    isStarted: Boolean,
    subscribeType: String,
},

data() {
    return {
        postEditModal: {
            isVisible: false,
        },
        postForEdit: null,
        postRepostModal: {
            isVisible: false,
        },
        postForRepost: null,
        postLikeModal: {
            isVisible: false,
            content: {
                postId: null,
            },
        },
    }
},
computed: {
    privacyLabel() {
        return communityUtils.getPrivacyLabel(this.community?.privacy);
    },
},

methods: {
    onSharePost(post) {
        this.postRepostModal.isVisible = true;
        this.postForRepost = post;
    },

    async onDeletePost(id) {
        let response;

        try {
            response = await this.$root.$api.$post.deletePost(id);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            const post = this.posts.find((post) => {
                return post.id === id;
            });

            post.deleted = true;

            this.startTimer(post);
        }
    },

    startTimer(post){
        setTimeout( () => {
            let postIndex = this.posts.findIndex(item => item.id === post.id);

            this.posts.splice( postIndex, 1 );
        }, 5000 );
    },

    async onRestorePost(id) {
        let response;

        try {
            response = await this.$root.$api.$post.restorePost(id);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            const post = this.posts.find((post) => {
                return post.id === id;
            });

            post.deleted = false;
        }
    },

    onEditPost(post) {
        this.postEditModal.isVisible = true;
        this.postForEdit = post;
    },

    hidePostEditModal(){
        this.postEditModal.isVisible = false;
        this.postForEdit = null;
    },

    openVideoModal(evData) {
        this.$emit('openVideoModal', evData);
    },

    openLikeModal(postId) {
        this.postLikeModal.isVisible = true;
        this.postLikeModal.content.postId = postId;
    },

    hidePostRepostModal() {
        this.postRepostModal.isVisible = false;
        this.postForRepost = null;
    },

    hideLikeModal() {
        this.postLikeModal.isVisible = false;
        this.postLikeModal.content.postId = null;
    },
}
}
</script>
