<template>
    <div class="plz-potential-friends-item d-flex align-items-center py-2 px-3">

        <router-link tag="a" class="plz-potential-friend-userpic text-body" :to="`/user-`+friend.id">
            <img class="plz-potential-userpic rounded-circle" :src="friend.userPic" :alt="friend.firstName"/>

            <span v-if="friend.isOnline" class="plz-potential-isonline" title="в сети"></span>
            <span v-else class="plz-potential-isoffline" :title="getSexTitle()"></span>
        </router-link>

        <div class="plz-potential-friend-title flex align-items-center mr-auto ">
            <router-link :to="`/user-`+friend.id" class="plz-potential-friend-name">
                {{friend.firstName}}
            </router-link>

            <div class="plz-potential-friend-status">
                <p v-html="$options.filters.mutualFriendsText(friend.mutualFriendsCount)"></p>
            </div>
        </div>

        <a class="plz-potential-friend-is-active text-body" @click="sendFriendshipInvitation(friend.id, friend.fullName)">
            <IconAddUser/>
        </a>
    </div>
</template>
<script>
import IconAddUser from '../icons/IconAddUser.vue';
import FriendshipInvitationMixin from '../mixins/FriendshipInvitationMixin.js';

export default {
name : 'ShortFriendItem',
components : {IconAddUser},
mixins: [FriendshipInvitationMixin],
props : {
    friend : Object
},

methods: {
    getSexTitle(){
        if (`m` === this.friend.sex)
            return `был давно`;

        if (`f` === this.friend.sex)
            return `была давно`;

        return `был(а) давно`;
    },
},
}
</script>
