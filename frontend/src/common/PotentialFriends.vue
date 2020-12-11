<template>
    <div id="potentialFriends"  class="plz-potential-friends --overflow-hidden mb-5">

        <div class="d-flex flex-row justify-content-start pb-3 --pt-5">
            <h6 class="plz-sf-title w-auto mt-2 ml-3">{{blockName}}</h6>
        </div>

        <div class="plz-potential-friends-list pb-2" >
            <PotentialFriendItem  v-for="friend in friendsList"
                              v-bind:friend="friend"
                              v-bind:key="friend.id"></PotentialFriendItem>
        </div>

    </div>
</template>

<script>
import PotentialFriendItem from './PotentialFriendItem.vue';

export default {
name: 'PotentialFriends',
components : { PotentialFriendItem },
props: {
    blockName : String,
    friends: Array
},
computed : {
    friendsList() {
        if (this.friends)
            return this.friends.slice(0, 5);

        return [];
    }
}

}
</script>


