<template>
    <li class="plizi-sr-item-user media m-0 py-4 px-4">
        <div class="plizi-sr-item d-flex w-100 align-items-center">

                <router-link v-if="isOwner" :to="`/profile`" tag="div" class="plizi-sr-item-pic mr-3 " >
                    <img class="plizi-sr-item-img rounded-circle overflow-hidden" v-bind:src="friend.profile.userPic" v-bind:alt="friendFullName" />
                    <span v-if="friend.isOnline" class="plizi-sr-item-isonline" title="онлайн"></span>
                    <span v-else class="plizi-sr-item-isoffline"></span>
                </router-link>
                <router-link v-else :to="`/user-`+friend.id" tag="div" class="plizi-sr-item-pic mr-3 " >
                    <img class="plizi-sr-item-img rounded-circle overflow-hidden" v-bind:src="friend.profile.userPic" v-bind:alt="friendFullName" />
                    <span v-if="friend.isOnline" class="plizi-sr-item-isonline" title="онлайн"></span>
                    <span v-else class="plizi-sr-item-isoffline"></span>
                </router-link>


            <div class="plizi-sr-item-body m-0 pr-5 ">
                <router-link v-if="isOwner"
                             :to="`/profile`"
                             tag="div"
                             class="plizi-sr-item-top d-flex align-items-end justify-content-between mb-2">
                    <h6 class="plizi-friend-item-name my-0">{{ friendFullName }}</h6>
                </router-link>
                <router-link v-else
                             :to="`/user-`+friend.id"
                             tag="div"
                             class="plizi-sr-item-top d-flex align-items-end justify-content-between mb-2">
                    <h6 class="plizi-friend-item-name my-0">{{ friendFullName }}</h6>
                </router-link>
                <div class="plizi-friend-item-body-bottom d-flex ">
                    <p class="plizi-friend-item-desc p-0 my-0 d-inline">

                        <IconLocation style="height: 14px;" />

                        <span v-if="friend.profile.location">
                            {{ `${friend.profile.location.title.ru}, ${friend.profile.location.country.title.ru}` }}
                        </span>
                        <span v-else>
                            Не указано
                        </span>
                    </p>
                </div>

            </div>

            <button @click.prevent="goToDialogWithFriend()" type="button" class="plz-short-friend-is-active btn btn-link text-body mr-2 ml-auto">
                <IconSpinner v-if="isInRedirecting" />
                <IconMessageShort v-else />
            </button>

            <a class="text-body" @click="sendFriendshipInvitation(friend.id, friendFullName)">
                <IconAddUser style="width: 24px; height: 24px;" />
            </a>

        </div>
    </li>
</template>

<script>
import IconLocation from '../icons/IconLocation.vue';
import IconMessage from '../icons/IconMessage.vue';
import IconMessageShort from '../icons/IconMessageShort.vue';
import IconSpinner from '../icons/IconSpinner.vue';
import IconAddUser from '../icons/IconAddUser.vue';

import DialogMixin from '../mixins/DialogMixin.js';
import FriendshipInvitationMixin from '../mixins/FriendshipInvitationMixin.js';

import PliziUser from '../classes/PliziUser.js';

export default {
name : 'UserFriendsListItem',
components: {IconMessageShort, IconAddUser, IconMessage, IconSpinner, IconLocation},
mixins: [FriendshipInvitationMixin, DialogMixin],
props : {
    friend : Object
},

data(){
    return {
        isInRedirecting: false,
        friendTransformed: {
            id: this.friend.id,
            fullName: `${this.friend.profile.firstName} ${this.friend.profile.lastName}`,
        }
    }
},

computed: {
    isOwner() {
       return this.friend.id === this.$root.$auth.user.id;
    },
    friendFullName() {
      const fullName = `${this.friend.profile.firstName} ${this.friend.profile.lastName}`;
      return fullName;
    },
},

methods: {
    async goToDialogWithFriend(){
        this.isInRedirecting = true;
        await this.openDialogWithFriend( this.friendTransformed );
        this.$root.$router.push('/chats');
    },
},
    mounted() {
    }
}

</script>

