<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3">

                <FriendsListHeader @filterSearch="filterSearch"></FriendsListHeader>
                <div class="d-flex flex-wrap align-items-start">

                    <div class="col-12 --order-1 --order-md-0 col-md-7 col-lg-8 col-xl-8 bg-white-br20">
                        <div id="friendsListFilter" class="row border-bottom px-4">
                            <div class="col-12 d-flex align-items-center justify-content-between px-0">
                                <nav class="nav profile-filter-links" role="tablist">
                                    <span class="nav-link position-relative py-2 py-sm-3  py-lg-4 px-1 mr-2 mr-lg-4"
                                          :class="{ 'active': wMode==='all' }" id="tabAllFriends" role="tab"
                                          @click.stop="friendsListSelect(`all`)">Все друзья</span>
                                    <span class="nav-link position-relative py-2 py-sm-3  py-lg-4 px-1 mr-2 mr-lg-4"
                                          :class="{ 'active': wMode==='online' }" id="tabOnlineFriends" role="tab"
                                          @click.stop="friendsListSelect(`online`)">Друзья онлайн</span>
                                    <span class="nav-link position-relative py-2 py-sm-3  py-lg-4 px-1 mr-2 mr-lg-4"
                                          :class="{ 'active': wMode==='recent' }" id="tabRecentFriends" role="tab"
                                          @click.stop="friendsListSelect(`recent`)">Новые друзья</span>
                                    <span class="nav-link position-relative py-2 py-sm-3  py-lg-4 px-1 mr-2 mr-lg-4"
                                          :class="{ 'active': wMode==='favorites' }" id="tabFavoritesFriends" role="tab"
                                          @click.stop="friendsListSelect(`favorites`)">Избранные</span>
                                </nav>
                            </div>
                        </div>

                        <FriendsAllList v-if="isFriendsLoaded"
                                        v-bind:friends="friendsListFilter()"
                                        v-bind:friendsKey="friendsListKey"
                                        v-bind:wMode="wMode"
                                        v-bind:frmSize="frmSize"
                                        v-bind:hasFriends="hasFriends">
                        </FriendsAllList>
                        <Spinner v-else></Spinner>
                    </div>

                    <div class="col-12 col-md-5 col-lg-4 col-xl-4">
                        <PotentialFriends v-if="possibleFriends && possibleFriends.length"
                                          v-bind:blockName="`Возможные друзья`"
                                          v-bind:friends="possibleFriends"></PotentialFriends>
                        <PotentialFriends v-if="recommendedFriends && recommendedFriends.length"
                                          v-bind:blockName="`Рекомендуемые друзья`"
                                          v-bind:friends="recommendedFriends"></PotentialFriends>
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
import FriendsAllList from '../components/FriendsAllList.vue';

import FriendsListMixin from '../mixins/FriendsListMixin.js';

import PliziFriend from '../classes/PliziFriend.js';
import PliziCollection from '../classes/PliziCollection.js';


export default {
name : 'FriendsListPage',
components : {
    FriendsAllList
},

mixins : [FriendsListMixin],

data(){
    return {
        allMyFriends: null,
        isFriendsLoaded: true,
        wMode : `all`,
        searchTerm: '',
        lazyLoadStarted: false,
        noMoreFriends: false,
        friendsListKey: 'friendsListKey'
    }
},

computed : {
    hasFriends(){
        return (this.$root.$auth.frm.size > 0);
    },

    frmSize(){
        return this.$root.$auth.frm.size;
    },
},


methods : {
    friendsListFilter(){
        let ret = [];

        if (this.wMode === 'all'){
            ret = this.$root.$auth.frm.asArray();
        }

        if (this.wMode === 'online'){
            this.$root.$auth.frm.asArray()
                .map(frItem => {
                    if (frItem.isOnline === true){
                        ret.push(frItem);
                    }
                });
        }

        if (this.wMode === 'recent'){
            this.$root.$auth.frm.asArray()
                .map(frItem => {
                    if ( frItem.friendshipLivingTime <= (3 * 86400) ){
                        ret.push(frItem);
                    }
                });
        }

        if (this.wMode === 'favorites'){
            this.$root.$auth.frm.asArray()
                .map(frItem => {
                    if ( this.$root.$auth.fm.checkIsFavorite( frItem.id )){
                        ret.push(frItem);
                    }
                });
        }

        /** @TGA как-то оно нелогично тут смотрится **/
        if (this.searchTerm.length > 2) {
            const searchTerm = this.searchTerm.toLocaleLowerCase();

            ret = ret.filter(friend => friend.fullName.toLowerCase().includes(searchTerm));
        }

        return ret;
    },

    friendsListSelect( wm ){
        this.wMode = wm;
    },

    /**
     * @deprecated
     * @returns {Promise<void>}
     */
    async loadMyFriends() {
        let apiResponse;

        try {
            apiResponse = await this.$root.$api.$friend.friendsList();
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.allMyFriends = new PliziCollection(apiResponse, PliziFriend);
            this.isFriendsLoaded = true;
        }
    },

    filterSearch({ searchTerm }) {
        this.searchTerm = searchTerm;
    },

    onScrollYPage(){
        if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight/2) )){
            this.friendsLazyLoad(); // Дозагрузка!
        }
    },

    async friendsLazyLoad(){
        if (this.lazyLoadStarted  ||  this.noMoreFriends)
            return;

        this.lazyLoadStarted = true;
        const added = await this.$root.$auth.frm.additionalLoad();

        this.$root.$auth.frm.collection.has( (new Date()).getMilliseconds()+``);

        this.friendsListKey = 'friendsListKey-'+this.$root.$auth.frm.size+'-'+(new Date()).getMilliseconds();

        this.$forceUpdate();

        if (added === 0){
            this.noMoreFriends = true;
        }

        this.lazyLoadStarted = false;

        this.onScrollYPage();
    }
},

created(){
    window.addEventListener('scroll', this.onScrollYPage);

    if ('#favorites' === this.$route.hash) {
        this.wMode = 'favorites';
    }

    this.$root.$on( this.$root.$auth.frm.updateEventName,()=>{
        this.friendsListKey = 'friendsListKey-'+this.$root.$auth.frm.size+'-'+(new Date()).getMilliseconds();
    });
}

}
</script>
