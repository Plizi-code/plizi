<template>
    <div class="plz-short-friends-item d-flex align-items-center py-2 px-3">

        <router-link tag="a" class="plz-short-friend-userpic text-body" :to="`/user-`+friend.id">
            <img class="plz-short-userpic rounded-circle" :src="friend.userPic" :alt="friend.firstName"/>

            <span v-if="friend.isOnline" class="plz-short-isonline" title="в сети"></span>
            <span v-else class="plz-short-isoffline" :title="getSexTitle(friend)"></span>
        </router-link>

        <div class="plz-short-friend-title flex align-items-center mr-auto ">
            <router-link :to="`/user-`+friend.id" class="plz-short-friend-name">
                {{friend.fullName}}
            </router-link>

            <div class="plz-short-friend-status">
                <p v-html="$options.filters.mutualFriendsText(friend.mutualFriendsCount)"></p>
            </div>
        </div>

        <button @click.prevent="goToDialogWithFriend()" type="button" class="btn btn-link plz-short-friend-is-active text-body">
            <IconSpinner v-if="isInRedirecting" />
            <IconMessageShort v-else />
        </button>
    </div>
</template>

<script>
import IconMessageShort from '../icons/IconMessageShort.vue';
import IconSpinner from '../icons/IconSpinner.vue';
import DialogMixin from '../mixins/DialogMixin.js';
import FriendItemMixin from '../mixins/FriendItemMixin.js';

export default {
name : 'ShortFriendItem',
components : { IconMessageShort, IconSpinner },
mixins : [DialogMixin, FriendItemMixin],

}
</script>
