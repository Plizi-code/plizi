import PliziFriend from '../classes/PliziFriend.js';
import PliziCollection from '../classes/PliziCollection.js';

export default {
data() {
    return {
        allMyFriends: null,
    };
},
methods: {
    async loadMyFriends() {
        let apiResponse;

        try {
            apiResponse = await this.$root.$api.$friend.friendsList();
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.allMyFriends = new PliziCollection(apiResponse, PliziFriend);
        }
    },
}
}
