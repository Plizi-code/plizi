<template>
    <li class="plizi-community-item-wrp col-12 col-sm-6 media px-2 mb-3 align-items-stretch">
        <div class="plizi-community-item d-flex flex-column flex-xl-row align-items-center justify-content-between bg-white-br20 w-100 px-4 py-4">

            <router-link :to="`/community-`+community.id" tag="a"
                         class="plizi-community-item-pic mr-auto ml-auto mr-xl-3 ml-xl-3 mb-3 mb-xl-0 rounded-circle overflow-hidden align-self-start">
                <img class="plizi-community-item-img "
                     :src="avatar" :alt="community.name" :title="community.name"/>
            </router-link>

            <div class="plizi-community-item-body">
                <router-link :to="`/community-`+community.id" tag="a"
                         class="plizi-community-item-top d-flex align-items-end justify-content-between mb-2  " >
                    <h6 class="plizi-community-item-name my-0">
                        {{ community.name }}
                    </h6>
                </router-link>

                <PrivacyLabel :community="community"></PrivacyLabel>

                <div class="plizi-community-item-body-middle mb-2">
                    <p v-if="community.description" class="plizi-community-item-desc p-0 mb-1">
                        {{ community.description }}
                    </p>
                    <p v-else-if="community.notice" class="plizi-community-item-notice p-0 my-0 text-secondary">{{ community.notice }}</p>
                    <p v-else class="plizi-community-item-location p-0 my-0 text-secondary">{{ locationLabel }}</p>

                    <p class="plizi-community-item-members-number p-0 my-0">{{ community.totalMembers }} участников </p>
                </div>

                <div class="plizi-community-item-body-bottom d-flex flex-column-reverse flex-xl-row align-items-center justify-content-between mt-3 mt-xl-0">
                    <button v-if="subscribeType === 'new'"
                            class="btn plz-btn-outline  plizi-community-btn rounded-pill"
                            @click="subscribeInvite(community)">
                        Подписаться
                    </button>
                    <button v-else-if="subscribeType === 'request'"
                            class="btn plz-btn-outline  plizi-community-btn rounded-pill"
                            @click="sendRequest(community)">
                        Запрос
                    </button>
                    <button v-else-if="subscribeType === 'exists'"
                            class="btn btn-outline-danger plizi-community-btn  rounded-pill"
                            @click="unsubscribeInvite(community)">
                        Отписаться
                    </button>
                    <router-link :to="{name: 'CommunitySettingsPage', params: {id: community.id}}" v-else-if="subscribeType === 'author'"
                                 class="btn btn-outline-danger plizi-community-btn  rounded-pill">
                        Управление
                    </router-link>
                    <div class="plizi-community-item-body-friends d-flex flex-wrap align-items-center justify-content-between ml-1" v-if="community.totalFriends">
                        <div class="plizi-community-item-body-friends-pics mr-1 my-1">
                            <div class="plizi-community-item-body-friends-pic position-relative rounded-circle"
                                v-for="friend in community.friends" :key="friend.id">
                                <img :src="getAvatar(friend)" :alt="friend.profile.fullName" :title="friend.profile.fullName"/>
                            </div>
                        </div>
                        <p class="plizi-community-item-desc my-1">{{community.totalFriends}} друзей</p>
                    </div>
                </div>
            </div>

        </div>
    </li>
</template>

<script>
import IconLocation from '../../icons/IconLocation.vue';
import IconMessage from '../../icons/IconMessage.vue';
import IconMessageShort from '../../icons/IconMessageShort';
import IconAddUser from '../../icons/IconAddUser.vue';

import PliziCommunity from '../../classes/PliziCommunity.js';
import CommunitiesSubscribeMixin from '../../mixins/CommunitiesSubscribeMixin.js';
import PrivacyLabel from "../../components/Community/PrivacyLabel.vue";

export default {
name : 'CommunityItem',
components: {PrivacyLabel, IconMessageShort, IconAddUser, IconMessage, IconLocation},
mixins: [CommunitiesSubscribeMixin],
props : {
    community : PliziCommunity,
},
computed: {
    avatar() {
        return this.community.avatar?.image.thumb.path || this.community.primaryImage;
    },
    subscribeType() {
        return this.getSubscribeType(this.community);
    },
    locationLabel() {
        const location = [];
        const country = this.community.location?.country.title.ru;
        if (country) {
            location.push(country);
        }
        const region = this.community.location?.region.title.ru;
        if (region) {
            location.push(region);
        }
        const city = this.community.location?.title.ru;
        if (city) {
            location.push(city);
        }
        return location.join(', ');
    }
},
methods: {
    /**
     * @param {PliziMember} user
     */
    getAvatar(user) {
        return user.profile.avatar?.image.thumb.path || user.profile.userPic;
    },
}
}
</script>

