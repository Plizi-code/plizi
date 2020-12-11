<template>
    <div v-if="isFriendsInCommunity"
         id="communityParticipants"
         class="bg-white-br20 mb-4 py-3 px-4 d-none d-lg-block">

        <div class="d-flex flex-row justify-content-start mb-1">
            <h6 class="plz-community-participants-title w-auto ">Участники
                <span class="plz-community-participants-subtitle ml-2">{{community.totalMembers}}</span>
            </h6>
        </div>

        <div class="plz-short-friends-list pb-2" >
            <CommunityShortMemberItem v-for="fmItem in friendsMembers"
                              v-bind:member="fmItem"
                              v-bind:key="fmItem.id"></CommunityShortMemberItem>
        </div>

        <div class="text-center" v-if="community.totalMembers > friendsMembers.length">
            <router-link tag="a"
                         class="plz-community-header-desc "
                         :to="`/members-`+community.id"><small>Все участники</small></router-link>
        </div>

    </div>
</template>

<script>
import CommunityShortMemberItem from './CommunityShortMemberItem.vue';
import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name: 'CommunityShortMembers',
components : { CommunityShortMemberItem },
props: {
    community: PliziCommunity
},

data () {
    return {
    }
},

computed : {
    friendsMembers(){
        return (this.community ? this.community.members : []);
    },

    isFriendsInCommunity(){
        return (this.community && this.community.totalMembers>0);
    },
}

}
</script>


