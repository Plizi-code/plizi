import PliziCommunity from "../classes/PliziCommunity";

const CommunitiesSubscribeMixin = {
    methods: {
        /**
         * @param {PliziCommunity} community
         * @returns {string}
         */
        getSubscribeType(community) {
            const role = community?.role;
            if (!role) {
                switch (community?.privacy) {
                    case 1://Open
                        return 'new';
                    case 2://Closed
                        return 'request';
                    default:
                        return '';
                }
            }
            if (role === 'author') {
                return 'author';
            }
            return 'exists';
        },

        /**
         * @param {PliziCommunity} community
         */
        subscribeInvite(community) {
            this.subscribeOnCommunity(community);
        },
        /**
         * @param {PliziCommunity} community
         */
        unsubscribeInvite(community) {
            this.unsubscribeCommunity(community);
        },
        /**
         * @param {PliziCommunity} community
         */
        sendRequest(community) {
            this.requestCommunity(community);
        },

        /**
         * @param {PliziCommunity} community
         * @returns {object|null}
         */
        async subscribeOnCommunity(community) {
            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$communities.subscribe(community.id);
            } catch (e) {
                window.console.warn(e.detailMessage);
                throw e;
            }

            if (apiResponse) {
                if (apiResponse.status && apiResponse.status === 422) {
                    this.$root.$notify(`Вы уже подписаны на ${community.name}`);
                } else {
                    community.role = 'user';
                    this.$root.$notify(`Вы успешно подписались на сообщество ${community.name}`);
                }
            } else {
                this.$root.$alert(`Не получилось подписаться на ${community.name}`, 'bg-warning', 3);
            }

            return true;
        },


        /**
         * @param {PliziCommunity} community
         * @returns {object|null}
         */
        async unsubscribeCommunity(community) {
            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$communities.unsubscribe(community.id);
            } catch (e) {
                window.console.warn(e.detailMessage);
                throw e;
            }

            if (apiResponse) {
                community.role = null;
                this.$root.$notify(`Вы успешно отписались от ${community.name}`);
            } else {
                this.$root.$alert(`Не получилось отписаться от ${community.name}`, 'bg-warning', 3);
            }

            return true;
        },

        /**
         * @param {PliziCommunity} community
         * @returns {object|null}
         */
        async requestCommunity(community) {
            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$communities.requestCreate(community.id);
            } catch (e) {
                window.console.warn(e.detailMessage);
                throw e;
            }

            if (apiResponse) {
                this.$root.$notify(apiResponse.message, 'bg-success', 3);
            } else {
                this.$root.$alert(`Ошибка отправки запроса`, 'bg-warning', 3);
            }

            return true;
        },
    },

};

export {CommunitiesSubscribeMixin as default}
