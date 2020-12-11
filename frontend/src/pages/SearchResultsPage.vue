<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-lg-9 col-xl-10  px-0 px-md-3">
                <div class="col-12 offset-xl-2 col-xl-8 bg-white-br20 p-4 mb-3">
                    <div v-if="isDataReady" class="plizi-search-results-list">
                        <h4>Люди</h4>
                        <hr>
                        <ul v-if="searchResults  &&  (searchResults.length > 0)" class="list-unstyled mb-0">
                            <SearchResultItem v-for="(srItem, srIndex) in searchResults"
                                              :key="srIndex" :srItem="srItem">
                            </SearchResultItem>
                        </ul>
                        <div v-else>
                            <div class="alert alert-info">
                                По Вашему запросу &quot;<b>{{$root.$lastSearch}}</b>&quot; ничего не найдено
                            </div>
                        </div>
                    </div>
                    <Spinner v-else v-bind:clazz="`d-flex flex-row`"></Spinner>
                </div>
                <div class="col-12 offset-xl-2 col-xl-8 bg-white-br20 p-4">
                    <div v-if="isCommunityDataReady" class="plizi-search-results-list">
                        <h4>Сообщества</h4>
                        <hr>
                        <ul v-if="communitySearchResults  &&  (communitySearchResults.length > 0)" class="list-unstyled mb-0">
                            <CommunitySearchResultItem v-for="(srItem, srIndex) in communitySearchResults"
                                              :key="srIndex" :community="srItem">
                            </CommunitySearchResultItem>
                        </ul>
                        <div v-else>
                            <div class="alert alert-info">
                                По Вашему запросу &quot;<b>{{$root.$lastSearch}}</b>&quot; ничего не найдено
                            </div>
                        </div>
                    </div>
                    <Spinner v-else v-bind:clazz="`d-flex flex-row`"></Spinner>
                </div>
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
import Spinner from '../common/Spinner.vue';

import SearchResultItem from '../components/SearchResultItem.vue';

import PliziUser from '../classes/PliziUser.js';
import PliziCommunity from "../classes/PliziCommunity.js";
import CommunitySearchResultItem from "../common/Communities/CommunitySearchResultItem.vue";

export default {
name: 'SearchResultsPage',
components: {
    CommunitySearchResultItem,
    SearchResultItem,
    AccountToolbarLeft, FavoriteFriends,
    Spinner
},
data() {
    return {
        searchResultsList: [],
        isDataReady : false,

        communitySearchResultsList: [],
        isCommunityDataReady : false,
    }
},

computed: {
    searchResults() {
        return this.searchResultsList;
    },
    communitySearchResults() {
        return this.communitySearchResultsList;
    }
},

methods: {
    async searchProcess(){
        await this.userSearchProcess();
        await this.communitySearchProcess();
    },
    async userSearchProcess(){
        this.isDataReady = true; // на случай если строка поиска пустая
        this.searchResultsList = [];

        if (this.$root.$lastSearch === ``)
            return;

        this.isDataReady = false;
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$users.search(this.$root.$lastSearch);
        }
        catch (e) {
            window.console.warn(e.detailMessage);
        }

        if (apiResponse !== null) {
            this.searchResultsList = [];

            apiResponse.map( (srItem)=> {
                this.searchResultsList.push( new PliziUser(srItem ) );
            });

            this.isDataReady = true;
        }
    },
    async communitySearchProcess(){
        this.isCommunityDataReady = true; // на случай если строка поиска пустая
        this.communitySearchResultsList = [];

        if (this.$root.$lastSearch === '')
            return;

        this.isCommunityDataReady = false;
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$communities.loadCommunities(this.$root.$lastSearch);
        }
        catch (e) {
            window.console.warn(e.detailMessage);
        }

        if (apiResponse !== null) {
            this.communitySearchResultsList = [];

            apiResponse.map( (srItem)=> {
                this.communitySearchResultsList.push( new PliziCommunity(srItem ) );
            });

            this.isCommunityDataReady = true;
        }
    }
},

beforeMount() {
    this.$root.$on('searchStart', this.searchProcess);
},

mounted(){
    const lst = localStorage.getItem('pliziLastSearch');

    if (lst  &&  lst!==``) {
        this.$root.$lastSearch = lst;
        this.searchProcess();
    }
}

}
</script>


<style lang="scss">
.chat-list-user {
    transition: .4s;

    &:hover {
        background-color: #e6e7eb !important;
    }

    .sr-item-desc {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>
