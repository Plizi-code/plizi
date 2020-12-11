<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3 ">
                <div class="row">
                    <CommunitiesListHeader list="popular"></CommunitiesListHeader>
                    <CommunityCreateBlock></CommunityCreateBlock>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8 col-xl-9 mb-4 px-4 py-0">
                        <div v-if="isPopularCommunitiesLoaded" class="row">
                            <ul v-if="popularCommunities  &&  popularCommunities.length > 0"
                                class="plizi-communities-list w-100 d-flex justify-content-between flex-wrap p-0">
                                    <CommunityItem v-for="(comItem, comIndex) in popularCommunities"
                                                   :community="comItem"
                                                   :key="comIndex">
                                    </CommunityItem>
                            </ul>
                            <div v-else-if="!enabledLoader" class="container px-2">
                                <div  class=" bg-white-br20 p-3">
                                    <div v-if="searchString.length === 0" class="alert alert-info w-100 py-4 text-center m-0">
                                        Нет ни одного популярного сообщества.
                                    </div>
                                    <div v-else class="alert alert-info w-100 py-4 text-center m-0">
                                        По Вашему запросу ничего не найдено.
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

                    <div class="col-12 col-lg-4 col-xl-3  mb-4  d-flex pl-3 pl-lg-0 ">
                        <CommunitiesSmallBlock v-if="isRecommendedCommunitiesDataReady"
                            :title="`Рекомендованные`"
                            :communities="recommendedCommunities"></CommunitiesSmallBlock>
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
import CommunitiesListMixin from '../mixins/CommunitiesListMixin.js';
import SmallSpinner from "../common/SmallSpinner.vue";

export default {
name : 'CommunitiesPopularPage',
components : {
    SmallSpinner,
},
mixins: [CommunitiesListMixin],

data(){
    return {
        list: 'popular',
    }
},

methods : {

},

async mounted(){
    await this.loadPopularCommunities(30);
    await this.loadRecommendedCommunities();
},
}
</script>
