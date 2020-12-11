<template>
    <div v-if="isFriendsInCommunity"
         id="communityParticipants"
         class="bg-white-br20 mb-4 py-3 px-4">

        <div class="d-flex flex-row justify-content-start mb-1">
            <h6 class="plz-community-participants-title w-auto ">Друзья
                <span class="plz-community-participants-subtitle ml-2">{{community.totalFriends}}</span>
            </h6>
        </div>

        <div class="plz-short-friends-list pb-2" >
            <CommunityShortMemberItem v-for="fmItem in friendsMembers"
                                      v-bind:member="fmItem"
                                      v-bind:key="fmItem.id"></CommunityShortMemberItem>
        </div>

        <div class="text-center" v-if="isMoreFriends">
            <router-link tag="a"
                         class="plz-community-header-desc "
                         :to="`/community-friends-`+community.id"><small>Смотреть ещё</small></router-link>
        </div>

    </div>
</template>

<script>
import PliziCommunity from '../../classes/PliziCommunity.js';
import CommunityShortMemberItem from "./CommunityShortMemberItem";

export default {
name : 'CommunityFriendsInformer',
components: {CommunityShortMemberItem},
props: {
    community: PliziCommunity
},
computed : {
    friendsMembers(){
        return this.community?.friends || [];
    },
    isMoreFriends() {
        return this.friendsMembers.length < parseInt(this.community.totalFriends);
    },
    isFriendsInCommunity() {
        return (this.community && this.community.totalFriends > 0);
    },
}
}
</script>
