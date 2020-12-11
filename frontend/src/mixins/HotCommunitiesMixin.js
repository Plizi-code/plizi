import PliziCommunity from '../classes/PliziCommunity.js';

const HotCommunitiesMixin = {

methods: {

    /**
     * @param {number} communityId
     * @param {PliziCommunity|null} community
     * @returns {boolean}
     */
    async addCommunityToFavorites(communityId, community = null) {
        const isCan = this.$root.$auth.cm.isCanAddToFavorites(communityId);

        if (! isCan) {
            return false;
        }

        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$communities.addToFavorites(communityId);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        if ( apiResponse ) {
            if (community) {
                this.$root.$auth.cm.onAddToFavorites( community.toJSON() );
            }

            this.$emit( 'CommunityAddToFavorites', {
                id: communityId,
                communityId: communityId,
            });

            this.$root.$notify(`Вы добавили сообщество <b>&laquo;${community.name}&raquo;</b> в Избранные`);
        }

        return true;
    },


    async removeCommunityFromFavorites(communityId, community) {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$communities.removeToFavorites(communityId);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        if ( apiResponse ) {
            this.$root.$auth.cm.removeFromFavorites(communityId);
            this.$root.$communitiesKeyUpdater++;

            this.$emit( 'CommunityRemoveFromFavorites', {
                id: communityId,
                communityId: communityId,
            });
        }

        return true;
    }

}

};

export {HotCommunitiesMixin as default}
