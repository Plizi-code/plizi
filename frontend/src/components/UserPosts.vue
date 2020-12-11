<template>
    <div>
        <ProfileFilter v-if="(filteredPosts && filteredPosts.length > 1) || filterMode !== 'all'"
                       v-bind:firstName="profileData.firstName"
                       @wallPostsSelect="wallPostsSelectHandler"/>
        <template v-if="filteredPosts && filteredPosts.length > 0">
            <Post v-for="postItem in filteredPosts"
                  v-waypoint="{ active: true, callback: ({ el, going, direction })=>{ postIsRead(el, going, direction); }, options: { threshold: [0.5] } }"
                  :key="`userPost-`+postItem.id"
                  :post="postItem"
                  @onShare="onSharePost"
                  @onShowUsersLikes="openLikeModal"></Post>
        </template>
        <div v-else-if="!isStarted" class="row plz-post-item mb-4 bg-white-br20 p-4">
            <div class="alert alert-info w-100 p-5 text-center mb-0">
                Пользователь {{ profileData.firstName }} не создал ни одной записи.
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

</template>

<script>
import SmallSpinner from '../common/SmallSpinner.vue';

import Post from '../common/Post/Post.vue';
import ProfileFilter from './ProfileFilter.vue';

import PostViewMixin from '../mixins/PostViewMixin.js';

export default {
name: 'UserPosts',
components: {Post, ProfileFilter, SmallSpinner},
mixins: [PostViewMixin],

props: {
    isStarted: Boolean,
    filterMode: {},
    filteredPosts: {},
    onSharePost: {},
    openLikeModal: {},
    profileData: {},
    wallPostsSelectHandler: {}
}

}
</script>
