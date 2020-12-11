<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-xl-8 px-0 px-md-3">
                <BlackListUsers v-bind:key="`blackList-`+blackListUpdater"
                                @RemoveFromBlackList="onRemoveFromBlackList"
                                v-bind:blockedUsers="blockedUsers"
                                v-bind:isBlacklistDataReady="isBlacklistDataReady"></BlackListUsers>
            </div>

            <div class="d-none d-xl-block col-sm-2 col-md-2 col-lg-2 col-xl-2  px-0 px-md-3">
                <BlackListSideMenu></BlackListSideMenu>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0  px-0 px-md-3">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
    import FavoriteFriends from '../common/FavoriteFriends.vue';
    import BlackListUsers from '../components/AccountSettings/BlackListUsers.vue';
    import BlackListSideMenu from '../components/AccountSettings/BlackListSideMenu.vue';
    import BlackListMixin from '../mixins/BlackListMixin.js';
    import PliziBlackListItem from '../classes/PliziBlackListItem.js';
    import PliziCollection from '../classes/PliziCollection.js';
    export default {
        name: 'BlackListPage',
        components: {
            BlackListSideMenu,
            AccountToolbarLeft, BlackListUsers,
            FavoriteFriends
        },
        mixins: [BlackListMixin],
        data() {
            return {
                blockedUsers: (new PliziCollection()),
                isBlacklistDataReady : false,
                blackListUpdater: 0
            }
        },
        methods: {
            onRemoveFromBlackList(evData){
                this.deleteFromBlacklist(evData.userId);
                this.blockedUsers.delete(evData.userId);
                this.blackListUpdater++;
            },
            async getBlacklist() {
                let apiResponse = null;
                try {
                    apiResponse = await this.$root.$api.$users.blacklist();
                }
                catch (e){
                    window.console.warn(e.detailMessage);
                    throw e;
                }
                if (apiResponse) {
                    this.blockedUsers.receive(apiResponse, PliziBlackListItem);
                    this.isBlacklistDataReady = true;
                }
            },
        },
        async mounted() {
            await this.getBlacklist();
        },
    }
</script>
