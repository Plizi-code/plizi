<template>
    <div class="plz-community-members-item d-flex align-items-center py-1 px-0">
        <router-link tag="a" class="plz-favorit-friend-userpic mr-2" :to="`/user-`+member.id">
            <img class="plz-favorit-userpic rounded-circle" :src="member.userPic"
                 :alt="member.firstName" />

            <span v-if="member.isOnline" class="plz-favorit-isonline" title="в сети"></span>
            <span v-else class="plz-favorit-isoffline"></span>
        </router-link>

        <div class="plz-favorit-friend-title flex align-items-center mr-auto text-left ">
            <router-link :to="`/user-`+member.id" class="plz-community-header-title text-bold">
                {{member.firstName}}
            </router-link>

            <div class="plz-short-member-status m-0 text-left" >
                <p class="plz-community-info-desc m-0">{{member.birthDate | calcAge}} лет </p>
            </div>
        </div>

    </div>
</template>

<script>
import PliziMember from '../../classes/PliziMember.js';

export default {
name : 'CommunityShortMemberItem',
components : {},
props : {
    member : PliziMember,
},

data(){
    return {
        isInRedirecting : false
    }
}

}
</script>
