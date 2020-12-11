<template>
    <div class="row plizi-friends-list" v-bind:key="friendsKey">

        <ul v-if="friends  &&  friends.length>0" class="d-block w-100 p-0" v-bind:key="calcFriendsKey+frmSize">
            <transition-group name="slide-fade" :duration="700">
                <FriendListItem v-for="friendItem in getFriends()"
                                @FriendRemoveFromFavorites="changesInFriends"
                                @FriendAddToFavorites="changesInFriends"
                                @FriendshipStop="changesInFriends"
                                v-bind:key="friendItem.id+`-`+calcFriendsKey"
                                v-bind:friend="friendItem">
                </FriendListItem>
            </transition-group>
        </ul>

        <div v-else class="alert alert-info w-100 p-5 text-center mb-0">
            <p v-if="wMode==='all'">Вы ещё ни с кем не подружились.</p>
            <p v-if="wMode==='recent'">Вы давно никого не добавляли в друзья.</p>
            <p v-if="wMode==='online'">Сейчас все друзей оффлайн.</p>
            <p v-if="wMode==='favorites'">Вы никого не добавили в Избранные.</p>
        </div>
    </div>

</template>

<script>
import FriendListItem from './FriendListItem.vue';

export default {
name : 'FriendsAllList',
components : { FriendListItem },
props : {
    friends : Array,
    friendsKey : String,
    frmSize : Number,
    wMode: String,
    hasFriends : Boolean
},

data(){
    return {
        friendsKeyCounter: 0,
        localUpdateKey: (new Date()).getMilliseconds(),
    };
},

computed: {
    calcFriendsKey(){
        return this.friendsKey + '-' + this.localUpdateKey;
    }
},

methods: {
    getFriends(){
        return this.friends.slice();
    },

    changesInFriends(){
        this.friendsKeyCounter += 1;
        this.localUpdateKey = this.friendsKeyCounter+'-'+(new Date()).getMilliseconds();
    }
},

created(){
    this.$root.$on( this.$root.$auth.frm.updateEventName,()=>{
        this.changesInFriends();
    });
}

}
</script>
