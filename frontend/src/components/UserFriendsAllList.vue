<template>
    <div class="row plizi-friends-list bg-white-br20 bg-white">
        <h6 class="w-100 media m-0 py-4 px-4 border-bottom">Список друзей</h6>

        <ul v-if="hasFriends" class="d-block w-100 p-0">
            <template v-if="isDataReady">
                <UserFriendsListItem v-for="friendItem in friendsList"
                                     :key="friendItem.id"
                                     :friend="friendItem">
                </UserFriendsListItem>
            </template>
                <Spinner v-else></Spinner>
        </ul>

        <div v-else class="alert alert-info w-100 p-5 text-center mb-0">
            <p>У этого пользователя нет ни одного друга.</p>
        </div>
    </div>

</template>

<script>
    import UserFriendsListItem from './UserFriendsListItem.vue';
    import Spinner from '../common/Spinner.vue';

    export default {
        name: 'UserFriendsAllList',
        components: {UserFriendsListItem, Spinner},

        data() {
            return {
                friendsList: null,
                isDataReady: false,
                hasFriends: true
            };
        },
        computed: {
          getUserId() {
              return this.$route.params.id;
          }
        },
        methods: {
            async getUserFriends() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$friend.friendsList(this.getUserId);
                } catch (e) {
                    this.isStarted = false;
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    this.friendsList = apiResponse;
                    this.isDataReady = true;
                    if (apiResponse.length === 0) {
                        this.hasFriends = false;
                    }
                }
            }
        },
        async mounted() {
            await this.getUserFriends();
        },

    }
</script>
