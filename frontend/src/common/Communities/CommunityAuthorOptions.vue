<template>
    <div id="communityAuthorOptions">
        <div class="nav-item">
            <router-link v-if="parseInt(community.privacy) === 2"
                         :to="{name: 'CommunityRequestsPage', params: {id: community.id}}"
                         class="community-options-link position-relative px-3 py-1">
                <i class="fas fa-user-cog mr-2"></i>Запросы
                <small v-if="community.requestsCount  > 0"
                       class="counter-info">{{community.requestsCount}}</small>
            </router-link>
        </div>
        <div class="nav-item">
            <router-link :to="{name: 'CommunityMembersPage', params: {id: community.id}}"
                         class="community-options-link px-3 py-1">
                <i class="fas fa-user mr-2"></i>Состав группы
            </router-link>
        </div>
        <div class="nav-item">
            <span class="community-options-link px-3 py-1 cursor-pointer"
                  @click="onMessageClick">
                <i class="fas fa-mail-bulk mr-2"></i>Сообщения
            </span>
        </div>
        <div class="nav-item border-top d-block" v-if="community.totalVideos > 0">
            <router-link :to="{name: 'CommunityVideosPage', params: {id: community.id}}"
                         class="community-options-link px-3 py-1">
                Видео сообщества
            </router-link>
        </div>
    </div>
</template>

<script>
import PliziCommunity from '../../classes/PliziCommunity.js';
export default {
name: 'CommunityAuthorOptions',
props: {
    community: PliziCommunity,
},
methods: {
    onMessageClick(){
        this.$root.$alert(`Сообщение`, 'bg-info', 3);
    },
}
}
</script>
