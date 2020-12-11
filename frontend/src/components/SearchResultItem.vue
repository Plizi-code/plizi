<template>
    <li class="plizi-sr-item-user media m-0 py-4 px-4">
        <div class="plizi-sr-item d-flex w-100 align-items-center">
            <router-link :to="`/user-`+srItem.id" tag="div" class="plizi-sr-item-pic mr-3 " >
                <img class="plizi-sr-item-img rounded-circle overflow-hidden" v-bind:src="srItem.userPic" v-bind:alt="srItem.fullName" />
                <span v-if="srItem.isOnline" class="plizi-sr-item-isonline" title="онлайн"></span>
                <span v-else class="plizi-sr-item-isoffline"></span>
            </router-link>

            <div class="plizi-sr-item-body m-0 pr-5 ">
                <router-link :to="`/user-`+srItem.id" tag="div"  class="plizi-sr-item-top d-flex align-items-end justify-content-between mb-2" >
                    <h6 v-html="toHighlightFullname" class="plizi-sr-item-name my-0"></h6>
                </router-link>

                <div class="plizi-sr-item-body-bottom d-flex pr-5">
                    <p class="plizi-sr-item-desc p-0 my-0  d-inline ">

                        <IconLocation style="height: 14px;" />

                        <span v-if="locationLabel" v-html="toHighlightLocation">
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

            <a class="text-body" @click="sendFriendshipInvitation(srItem.id, srItem.fullName)">
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
name : 'SearchResultItem',
components: {IconMessageShort, IconAddUser, IconMessage, IconSpinner, IconLocation},
mixins: [FriendshipInvitationMixin, DialogMixin],
props : {
    srItem : PliziUser
},

data(){
    return {
        isInRedirecting: false
    }
},

computed: {
    toHighlightFullname() {
        const fullName = this.srItem.fullName;
        let sr = this.$root.$lastSearch;
        let srName = fullName.replace(new RegExp(`${sr}`, 'ig'), fullName =>
            `<span class="bg-warning">${fullName}</span>`);
        return srName;
    },
    toHighlightLocation() {
        const location = this.locationLabel;
        let sr = this.$root.$lastSearch;
        let srLocation = location.replace(new RegExp(`${sr}`, 'ig'), location =>
            `<span class="bg-warning">${location}</span>`);
        return srLocation;
    },
    locationLabel() {
        const location = [];
        const country = this.srItem.location?.country?.title.ru;
        if (country) {
            location.push(country);
        }
        const region = this.srItem.location?.region?.title.ru;
        if (region) {
            location.push(region);
        }
        const city = this.srItem.location?.title.ru;
        if (city) {
            location.push(city);
        }
        return location.join(', ');
    }
},

methods: {
    async goToDialogWithFriend(){
        this.isInRedirecting = true;
        await this.openDialogWithFriend( this.srItem );
        this.$root.$router.push('/chats');
    },
},
    mounted() {
    }
}

</script>

