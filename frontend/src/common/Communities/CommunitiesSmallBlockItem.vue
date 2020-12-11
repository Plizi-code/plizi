<template>
    <div class="plz-potential-friends-item d-flex align-items-center py-2 px-3">

        <router-link tag="a" class="plz-potential-friend-userpic text-body" :to="{name: 'CommunityPage', params: {id: community.id}}">
            <img class="plz-potential-userpic rounded-circle" :src="getCommunityAvatar(community)" :alt="community.name"/>
        </router-link>

        <div class="plz-potential-friend-title flex align-items-center mr-auto ">
            <router-link :to="{name: 'CommunityPage', params: {id: community.id}}" class="plz-potential-friend-name">
                {{community.name}}
            </router-link>

            <div class="plz-potential-friend-status">
                <p v-html="community.noticeShort"></p>
            </div>
        </div>
    </div>
</template>

<script>
    import PliziCommunity from "../../classes/PliziCommunity.js";
    import AvatarMixin from "../../mixins/AvatarMixin.js";

    export default {
        name: 'CommunitiesSmallBlockItem',
        mixins: [AvatarMixin],
        props: {
            community : PliziCommunity
        },
    }
</script>
