<template>
    <li class="plizi-sr-item-user plizi-sr-item col-12 --col-lg-6 media px-2 py-3 mb-3 align-items-stretch">
        <div class="plizi-community-item d-flex flex-column flex-lg-row align-items-center justify-content-between --bg-white-br20 w-100 --px-4 --py-4">

            <router-link :to="`/community-`+community.id" tag="a"
                         class="plizi-sr-item-pic mr-auto ml-auto mr-lg-3 ml-lg-3 mb-3 mb-lg-0 rounded-circle overflow-hidden align-self-start">
                <img class="plizi-community-item-img "
                     :src="avatar" :alt="community.name" :title="community.name"/>
            </router-link>

            <div class="plizi-sr-item-body ">
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

                <div class="plizi-community-item-body-bottom d-flex flex-column-reverse flex-lg-row align-items-center justify-content-between mt-3 mt-lg-0">

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
    import PrivacyLabel from "../../components/Community/PrivacyLabel.vue";

    export default {
        name: 'GuestCommunitySearchResultItem',
        components: {PrivacyLabel, IconMessageShort, IconAddUser, IconMessage, IconLocation},
        props: {
            community: PliziCommunity,
        },
        computed: {
            toHighlightName() {
                const communityName = this.community.name;
                let sr = this.$root.$lastSearch;
                let srName = communityName.replace(new RegExp(`${sr}`, 'ig'), communityName =>
                    `<span class="bg-warning">${communityName}</span>`);
                return srName;
            },
            toHighlightCommunityLocation() {
                const communityLocation = this.locationLabel;
                let sr = this.$root.$lastSearch;
                let srLocation = communityLocation.replace(new RegExp(`${sr}`, 'ig'), communityLocation =>
                    `<span class="bg-warning">${communityLocation}</span>`);
                return srLocation;
            },
            toHighlightDescription() {
                const communityDescription = this.community.description;
                let sr = this.$root.$lastSearch;
                let srDescription = communityDescription.replace(new RegExp(`${sr}`, 'ig'), communityDescription =>
                    `<span class="bg-warning">${communityDescription}</span>`);
                return srDescription;
            },
            toHighlightNotice() {
                const communityNotice = this.community.notice;
                let sr = this.$root.$lastSearch;
                let srNotice = communityNotice.replace(new RegExp(`${sr}`, 'ig'), communityNotice =>
                    `<span class="bg-warning">${communityNotice}</span>`);
                return srNotice;
            },
            avatar() {
                return this.community.avatar?.image.thumb.path || this.community.primaryImage;
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

