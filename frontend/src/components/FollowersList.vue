<template>
    <div class="row plizi-friends-list bg-white-br20 bg-white">
        <h6 class="w-100 media m-0 py-4 px-4 border-bottom">На кого подписан</h6>

        <ul class="d-block w-100 p-0">
            <template v-if="isDataReady">
                <UserFriendsListItem v-for="friendItem in followersList"
                                     :key="friendItem.id"
                                     :friend="friendItem">
                </UserFriendsListItem>
            </template>
            <template v-if="enabledLoader">
                <li class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <div class="w-100 p-5 text-center mb-0">
                        <SmallSpinner/>
                    </div>
                </li>
            </template>
        </ul>

        <div v-if="isDataReady && followersList.length === 0" class="alert alert-info w-100 p-5 text-center m-3">
            <p>Этот пользователь ни на кого не подписан.</p>
        </div>
    </div>

</template>

<script>
    import PliziUser from "../classes/PliziUser.js";

    import UserFriendsListItem from './UserFriendsListItem.vue';
    import SmallSpinner from "../common/SmallSpinner.vue";

    export default {
        name: 'CommunityFollowersList',
        components: {SmallSpinner, UserFriendsListItem},

        data() {
            return {
                noMore: false,
                enabledLoader: true,

                isDataReady: false,
                followersList: [],
            };
        },
        computed: {
          getUserId() {
              return this.$route.params.id;
          }
        },
        methods: {
            async onScrollYPage() {
                if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                    if (this.$route.name === 'userVideoList') {
                        await this.lazyLoad('userVideoList');
                    }
                }
            },

            async loadUserFollowList(limit = 20, offset = 0) {
                this.enabledLoader = true;
                let apiResponse = null;

                if (offset === 0) {
                    this.isDataReady = false;
                }

                try {
                    apiResponse = await this.$root.$api.$users.userFollowList(this.userId, limit, offset);
                } catch (e) {
                    this.enabledLoader = false;
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                return this.processApiResponse(offset, apiResponse, 'followersList');
            },

            processApiResponse(offset, apiResponse, list) {
                this.enabledLoader = false;
                this.isDataReady = true;
                if (apiResponse) {
                    if (offset === 0) {
                        this[list] = [];
                    }
                    apiResponse.list.map((pfItem) => {
                        this[list].push(new PliziUser(pfItem));
                    });

                    return apiResponse.list.length;
                }

                return 0;
            },

            async lazyLoad(listName) {
                if (this.noMore || this.enabledLoader) return;

                this.enabledLoader = true;
                const oldSize = this[listName].length;
                const added = await this.loadUserFollowList(20, oldSize + 1);

                if (added === 0) {
                    this.noMore = true;
                }

                this.enabledLoader = false;
            },
        },
        async mounted() {
            this.userId = this.$route.params.id;
            await this.loadUserFollowList();
            window.addEventListener('scroll', this.onScrollYPage);
        },
        beforeRouteLeave(to, from, next) {
            window.removeEventListener('scroll', this.onScrollYPage);
            next();
        },

    }
</script>
