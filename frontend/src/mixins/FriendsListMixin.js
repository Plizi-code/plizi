import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import Spinner from '../common/Spinner.vue';

import FavoriteFriends from '../common/FavoriteFriends.vue';
import IconSearch from '../icons/IconSearch.vue';

import FriendsListHeader from '../common/FriendsListHeader.vue';
import PotentialFriends from '../common/PotentialFriends.vue';

import PliziUser from '../classes/PliziUser.js'; // @TGA не удалять - объявление PliziUser тут нужно
import PliziFriend from '../classes/PliziFriend.js';
import { shuffle } from '../utils/ArrayUtils.js';

const FriendsListMixin = {
components: {
    IconSearch,
    AccountToolbarLeft,
    FavoriteFriends,
    Spinner,
    FriendsListHeader, PotentialFriends,
},

data() {
    return {
        potentialList : [],

        possibleFriends: null,
        recommendedFriends: null,
    }
},

methods: {
    /**
     * @param a
     * @returns {*}
     */
    shuffle,

    /**
     * Получение возможных друзей.
     * @returns {object[]|null}
     */
    async loadPossibleFriends() {
        let response;

        try {
            response = await this.$root.$api.$friend.getPossibleFriends();
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            this.possibleFriends = [];

            response.map((possibleFriend) => {
                this.possibleFriends.push(new PliziFriend(possibleFriend));
            });

            this.$root.$emit('loadPossibleFriends');
        }
    },


    /**
     * Получение рекомендуемых друзей.
     * @returns {object[]|null}
     */
    async loadRecommendedFriends() {
        let response;

        try {
            response = await this.$root.$api.$friend.getRecommendedFriends();
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            this.recommendedFriends = [];

            response.map((recommendedFriend) => {
                this.recommendedFriends.push(new PliziFriend(recommendedFriend));
            });

            this.$root.$emit('loadRecommendedFriends');
        }
    },
},

created(){
    this.loadPossibleFriends();
    this.loadRecommendedFriends();
},

};

export {FriendsListMixin as default}
