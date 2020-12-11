<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3 ">

                <FriendsListHeader></FriendsListHeader>

                <div class="d-flex flex-wrap align-items-start">
                    <div class="col-12 --order-1 --order-md-0 col-md-7 col-lg-8 col-xl-8 bg-white-br20">
                        <FollowList v-if="isDataReady"
                                    @unFollow="unFollow"
                                    :follows="followList"
                                    :followsNumber="followsNumber"></FollowList>
                        <Spinner v-else :clazz="`d-flex flex-row`"></Spinner>
                    </div>

                    <div class="col-12 col-md-5 col-lg-4 col-xl-4">
                        <PotentialFriends v-if="possibleFriends && possibleFriends.length"
                                          :blockName="`Возможные друзья`"
                                          :friends="shuffle(possibleFriends)"></PotentialFriends>
                        <PotentialFriends v-if="recommendedFriends && recommendedFriends.length"
                                          :blockName="`Рекомендуемые друзья`"
                                          :friends="shuffle(recommendedFriends)"></PotentialFriends>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-xl-1 d-none d-lg-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import FriendsListMixin from '../mixins/FriendsListMixin.js';

    import InvitationsList from '../components/InvitationsList.vue';
    import PliziUser from "../classes/PliziUser.js";
    import FollowList from "../components/Follow/FollowList.vue";

    export default {
        name: 'FollowListPage',
        components: {
            FollowList,
            InvitationsList
        },
        mixins: [FriendsListMixin],
        data() {
            return {
                followList: [],
                isDataReady: false,
                followsNumber: 0,
            };
        },
        methods: {
            unFollow() {
                this.getList();
            },
            async getList() {
                this.isDataReady = false;
                let response;

                try {
                    response = await this.$root.$api.$users.followList();
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.followList = [];
                    this.followsNumber = response.totalCount;

                    response.list.map((possibleFriend) => {
                        this.followList.push(new PliziUser(possibleFriend));
                    });
                }

                this.isDataReady = true;
            }
        },
        mounted() {
            this.getList();
        },
    }
</script>
