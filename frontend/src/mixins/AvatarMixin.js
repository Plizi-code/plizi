import PliziCommunity from "../classes/PliziCommunity.js";

const AvatarMixin = {
    methods: {
        /**
         * @param {PliziCommunity} community
         * @param {string} size
         */
        getCommunityAvatar(community, size = 'thumb') {
            return community?.avatar?.image[size]?.path || community?.primaryImage || (new PliziCommunity({})).defaultAvatarPath;
        }
    }
};

export {AvatarMixin as default}
