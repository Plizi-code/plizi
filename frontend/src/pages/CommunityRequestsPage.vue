<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-xl-8 " v-if="isDataReady">

                <div v-if="this.requests.length > 0" class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <ul class="d-block w-100 p-0">
                        <CommunityRequestItem v-for="request in requests"
                              :key="request.id"
                              :request="request"
                              :communityId="id"
                              @accepted="refreshList"
                              @rejected="refreshList">
                        </CommunityRequestItem>
                    </ul>
                </div>
                <div v-else class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <div class="alert alert-info w-100 p-5 text-center mb-0">
                        Нет новых запросов на вступление в сообщество
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-9 col-xl-8 " v-else>
                <Spinner></Spinner>
            </div>

            <div class="d-none d-xl-block col-sm-2 col-md-2 col-lg-2 col-xl-2">
                <CommunityRequestsSideMenu :id="id"></CommunityRequestsSideMenu>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
    import FavoriteFriends from '../common/FavoriteFriends.vue';
    import CommunityRequestsSideMenu from "../components/CommunitySettings/CommunityRequestsSideMenu.vue";
    import CommunityRequestItem from "../components/CommunitySettings/CommunityRequestItem.vue";
    import Spinner from "../common/Spinner.vue";
    import PliziCommunityRequest from "../classes/Community/PliziCommunityRequest.js";

    export default {
        name: 'CommunityRequestsPage',
        props: {
            id: Number | String
        },
        components: {
            Spinner,
            AccountToolbarLeft,
            FavoriteFriends,
            CommunityRequestsSideMenu,
            CommunityRequestItem,
        },
        data() {
            return {
                isDataReady: false,
                requests: [],
            }
        },
        methods: {
            async getCommunityRequestsList() {
                let apiResponse = null;
                this.isDataReady = false;

                try {
                    apiResponse = await this.$root.$api.$communities.requestList(this.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }
                //
                if (apiResponse) {
                    this.requests = [];

                    apiResponse.data.list.map((request) => {
                        this.requests.push(new PliziCommunityRequest(request));
                    });

                    this.isDataReady = true;
                }
            },
            refreshList() {
                this.getCommunityRequestsList();
            },
        },
        mounted() {
            this.getCommunityRequestsList();
        }
    }
</script>

