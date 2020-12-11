<template>
    <div class="row">
        <div class="col-12">
            <div class="row" v-if="isUserCommunitiesDataReady">
                <ul v-if="userCommunities  &&  userCommunities.length > 0"
                    class="plizi-communities-list w-100 d-flex justify-content-between flex-wrap p-0">
                    <CommunityItem v-for="comItem in userCommunities"
                                   :community="comItem"
                                   :key="`ucl_` + comItem.id">
                    </CommunityItem>
                </ul>

                <div v-else-if="!enabledLoader" class="container px-2 ">
                    <div  class=" bg-white-br20 p-3">
                        <div class="alert alert-info w-100 py-4 text-center m-0">
                            Пользователь еще не присоединился ни к одному сообществу.
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="enabledLoader">
                <div class="row plz-post-item mb-4 bg-white-br20 p-4">
                    <div class="w-100 p-5 text-center mb-0">
                        <SmallSpinner/>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import CommunitiesListMixin from "../mixins/CommunitiesListMixin.js";

    import SmallSpinner from "../common/SmallSpinner.vue";

    export default {
        name: "CommunityTemplate",
        components: {SmallSpinner},
        mixins: [CommunitiesListMixin],
        data() {
            return {
                userId: null,
            }
        },
        computed: {
            getUserId() {
                return this.$route.params.id;
            }
        },
        async mounted() {
            this.userId = this.$route.params.id;
            await this.getUserCommunitiesList(20);
        },
    }
</script>

<style scoped>

</style>
