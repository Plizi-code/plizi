<template>
    <div v-if="showFavoritesBlock" id="favoriteFriends" class="plz-favorit-friends --overflow-hidden mb-5"
         :class="{ 'bg-white-br20' : !isPickFavorite, 'is-pick-favorite':isPickFavorite } "
         :key="`favoriteFriends-`+favorUpdated">

        <div v-if="isDataReady" class="--d-flex --flex-row --justify-content-start pb-3 --border-bottom pt-3" :key="`favoriteFriendsInner-`+favorUpdated">
            <router-link tag="h6" to="/friends#favorites" v-if="!isNarrow" class="plz-ff-title mt-2 ml-3 d-block cursor-pointer">Сообщества пользователя</router-link>

<!--            <a href="#" class="w-auto ml-auto &#45;&#45;align-self-end mr-3 mt-2 text-body" title="свернуть">-->
<!--                <i class="fas fa-chevron-right"></i>-->
<!--            </a>-->

            <div class="plz-favorit-friends-list pb-2">
                <FavoriteFriendItem v-for="friend in getFavoriteFriends(favorUpdated)"
                                    @PickFavorite="onPickFavorite"
                                    @UnPickFavorite="onUnPickFavorite"
                                    v-bind:friend="friend"
                                    v-bind:pickedFavoriteId="pickedFavoriteId"
                                    v-bind:isNarrow="isNarrow"
                                    v-bind:key="(friend.id+'-'+favorUpdated)">
                </FavoriteFriendItem>
            </div>
        </div>
        <Spinner v-else clazz="plz-favorit-friends-spinner d-flex flex-column align-items-center"></Spinner>
    </div>
</template>

<script>
import FavoriteFriendItem from './FavoriteFriendItem.vue';
import Spinner from '../common/Spinner.vue';

export default {
name: 'UserCommunitiesList',
components : { Spinner, FavoriteFriendItem },
props : {
    isNarrow: {
        type: Boolean,
        required: false,
        default: false
    },
    communities: Array
},
data () {
    return {
        showFavoritesBlock: true,
        isDataReady : false,
        isPickFavorite: false,
        pickedFavoriteId: `none`,
        favorUpdated: 1
    }
},

methods : {
    getFavoriteFriends(parasm){
        return this.$root.$auth.fm.asArray().slice();
    },

    onPickFavorite(evData){
        this.isPickFavorite = true;
        this.pickedFavoriteId = evData.friendId;
    },

    onUnPickFavorite(evData){
        this.isPickFavorite = false;
        this.pickedFavoriteId = 'none';
    },

    afterFavoritsLoad(msg){
        this.favorUpdated++;
        this.showFavoritesBlock = (((this.favorUpdated * 0) + this.$root.$auth.fm.size) > 0);
        this.isDataReady = true;
    },

    updateOnlineStatus(evData) {
        this.$root.$auth.fm.updateOnlineStatus(evData.message.userId);
        this.$root.$favoritesKeyUpdater++;

        this.$root.$auth.frm.updateOnlineStatus(evData.message.userId);
        this.$root.$friendsKeyUpdater++;
    },
},

created(){
    if (this.$root.$auth.fm.isLoad) {
        this.afterFavoritsLoad(`this.$root.$auth.fm.isLoad`);
    }

    this.$root.$on(this.$root.$auth.fm.loadEventName, ()=>{
        this.afterFavoritsLoad(this.$root.$auth.fm.loadEventName);
    });

    this.$root.$on(this.$root.$auth.fm.restoreEventName, ()=>{
        this.afterFavoritsLoad(this.$root.$auth.fm.restoreEventName);
    });

    this.$root.$on(this.$root.$auth.fm.updateEventName, ()=>{
        this.afterFavoritsLoad(this.$root.$auth.fm.updateEventName);
    });

    this.$root.$on('newMessageInDialog', this.updateOnlineStatus);
}

}
</script>
