import PliziCommunity from "../classes/PliziCommunity.js";

import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';
import Spinner from '../common/Spinner.vue';

import CommunitiesListHeader from '../common/Communities/CommunitiesListHeader.vue';
import CommunityCreateBlock from '../common/Communities/CommunityCreateBlock.vue';
import CommunityItem from '../common/Communities/CommunityItem.vue';
import CommunitiesSmallBlock from '../common/Communities/CommunitiesSmallBlock.vue';

const CommunitiesListMixin = {
    components: {
        AccountToolbarLeft,
        Spinner,
        CommunitiesListHeader,
        CommunityItem,
        CommunityCreateBlock,
        CommunitiesSmallBlock,
        FavoriteFriends,
    },

    data() {
        return {
            isCommunitiesLoaded: false,
            communitiesList: [],

            isPopularCommunitiesLoaded: true,
            popularCommunities: [],

            isManagedCommunitiesLoaded: false,
            managedCommunities: [],

            isRecommendedCommunitiesDataReady: false,
            recommendedCommunities: [],

            isUserCommunitiesDataReady: false,
            userCommunities: [],

            noMore: false,
            enabledLoader: true,

            searchString: '',

            isDataReady: false,
        }
    },
    mounted() {
        this.$root.$on('communitySearchStart', this.searchProcess);
        window.addEventListener('scroll', this.onScrollYPage);
        this.searchString = window.localStorage.getItem('lastCommunitiesSearch_' + this.list) || '';
    },
    beforeRouteLeave(to, from, next) {
        window.removeEventListener('scroll', this.onScrollYPage);
        next();
    },
    beforeDestroy() {
        this.$root.$off('communitySearchStart', this.searchProcess);
    },

    methods: {
        async searchProcess(e) {
            this.noMore = false;
            this.searchString = e.searchText;
            if (e.list === 'my') {
                return this.loadCommunities();
            }
            if (e.list === 'owner') {
                return this.loadManagedCommunities();
            }
            return this.loadPopularCommunities();
        },
        isSubscribed(commID) {
            if (!this.popularCommunities)
                return true;

            const ret = !!this.popularCommunities.find((commItem) => {
                //window.console.info(commItem.id, commItem.name);
                return commItem.id === commID;
            });
            //window.console.log(ret, commID);

            return ret;
        },
        async onScrollYPage() {
            if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                // console.log(this.$route.name);

                if (this.$route.name === 'CommunitiesManagePage') {
                    await this.lazyLoad('manage');
                } else if (this.$route.name === 'CommunitiesPopularPage') {
                    await this.lazyLoad('popular');
                } else if (this.$route.name === 'userCommunities') {
                    await this.lazyLoad('userCommunities');
                } else {
                    await this.lazyLoad();
                }
            }
        },

        async loadCommunities(limit = 10, offset = 0) {
            this.enabledLoader = true;
            const searchText = this.searchString;
            let apiResponse = null;

            if (offset === 0) {
                this.isCommunitiesLoaded = false;
            }

            try {
                apiResponse = await this.$root.$api.$communities.userCommunities(searchText, limit, offset);
            } catch (e) {
                this.enabledLoader = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            this.isCommunitiesLoaded = true;
            this.enabledLoader = false;

            return this.processApiResponce(offset, apiResponse, 'communitiesList');
        },

        async loadPopularCommunities(limit = 10, offset = 0) {
            this.enabledLoader = true;
            const searchText = this.searchString;
            let apiResponse = null;

            if (offset === 0) {
                this.isPopularCommunitiesLoaded = false;
            }

            try {
                apiResponse = await this.$root.$api.$communities.loadCommunities(searchText, limit, offset);
            } catch (e) {
                this.enabledLoader = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            this.isPopularCommunitiesLoaded = true;
            this.enabledLoader = false;

            return this.processApiResponce(offset, apiResponse, 'popularCommunities');
        },

        async loadManagedCommunities(limit = 10, offset = 0) {
            this.enabledLoader = true;
            const searchText = this.searchString;
            let apiResponse = null;

            if (offset === 0) {
                this.isManagedCommunitiesLoaded = false;
            }

            try {
                apiResponse = await this.$root.$api.$communities.loadManagedCommunities(searchText, limit, offset);
            } catch (e) {
                this.enabledLoader = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            this.isManagedCommunitiesLoaded = true;
            this.enabledLoader = false;

            return this.processApiResponce(offset, apiResponse, 'managedCommunities');
        },

        processApiResponce(offset, apiResponse, list) {
            if (apiResponse) {
                this.enabledLoader = false;
                this.isCommunitiesLoaded = true;

                if (offset === 0) {
                    this[list] = [];
                }
                apiResponse.map((pfItem) => {
                    this[list].push(new PliziCommunity(pfItem));
                });

                return apiResponse.length;
            }

            return 0;
        },

        async loadRecommendedCommunities() {
            this.isRecommendedCommunitiesDataReady = false;
            // this.communities = [];
            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$communities.recommended()
            } catch (e) {
                window.console.warn(e.detailMessage);
            }

            if (apiResponse !== null) {
                this.communities = [];
                apiResponse.list.map((srItem) => {
                    this.recommendedCommunities.push(new PliziCommunity(srItem));
                });

                this.isRecommendedCommunitiesDataReady = true;
            }
        },

        async getUserCommunitiesList(limit = 10, offset = 0) {
            let apiResponse = null;
            this.isUserCommunitiesDataReady = false;

            try {
                apiResponse = await this.$root.$api.$users.getUserCommunities(this.userId, limit, offset);
            }
            catch (e){
                this.isStarted = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            this.isUserCommunitiesDataReady = true;
            this.enabledLoader = false;

            return this.processApiResponce(offset, apiResponse.list, 'userCommunities');
        },

        async lazyLoad(listName = null) {
            if (this.noMore || this.enabledLoader) return;

            this.enabledLoader = true;

            let oldSize, added;

            if (listName === 'manage') {
                oldSize = this.managedCommunities.length;

                if (oldSize)
                    added = await this.loadManagedCommunities(20, oldSize++);
            } else if (listName === 'popular') {
                oldSize = this.popularCommunities.length;

                if(oldSize)
                    added = await this.loadPopularCommunities(20, oldSize++);
            } else if (listName === 'userCommunities') {
                oldSize = this.userCommunities.length;

                if(oldSize)
                    added = await this.getUserCommunitiesList(20, oldSize++);
            } else {
                oldSize = this.communitiesList.length;

                if(oldSize)
                    added = await this.loadCommunities(20, oldSize++);
            }

            if (added === 0) {
                this.noMore = true;
            }

            this.enabledLoader = false;
        },
    },

};

export {CommunitiesListMixin as default}
