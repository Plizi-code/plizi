<template>
    <div id="communityUserOptions">
        <div class="nav-item">
            <span @click="onAddPostClick"
                    class="community-options-link px-3 py-1 cursor-pointer">
                Написать сообщение
            </span>
        </div>
        <div class="nav-item">
            <span v-if="community.subscribed" @click="onNotificationDisableClick"
                    class="community-options-link px-3 py-1 cursor-pointer">
                Отключить уведомления
            </span>
            <span v-else @click="onNotificationEnableClick"
                  class="community-options-link px-3 py-1 cursor-pointer">
                Включить уведомления
            </span>
        </div>
        <div class="nav-item">
            <span @click="onFriendInformationClick"
                  class="community-options-link px-3 py-1 cursor-pointer">
                Рассказать друзьям
            </span>
        </div>
        <div class="nav-item border-top d-block" v-if="community.totalVideos > 0">
            <router-link :to="{name: 'CommunityVideosPage', params: {id: community.id}}"
                         class="community-options-link px-3 py-1">
                Видео сообщества
            </router-link>
        </div>
        <div class="nav-item  d-block">
            <router-link :to="{name: 'CommunityMembersPage', params: {id: community.id}}"
                         class="community-options-link px-3 py-1">
                Участники сообщества
            </router-link>
        </div>
    </div>
</template>
<script>
import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name: 'CommunityUserOptions',
props: {
    community: PliziCommunity
},
methods: {
    onAddPostClick(){
        this.$root.$alert(`Тут наверное будет модалка для добавления поста`, 'bg-info', 3);
    },

    onMentionClick(){
        this.$root.$alert(`Упоминание`, 'bg-info', 3);
    },

    async onNotificationEnableClick(){
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$communities.subscribeNotify(this.community.id);
        } catch (e) {
            window.console.warn(e.detailMessage);
            this.$root.$alert(`Не получилось включить нотификации`, 'bg-warning', 3);
            throw e;
        }

        if (apiResponse) {
            this.community.subscribed = true;
            this.$root.$alert(`Включение нотификаций`, 'bg-success', 3);
        } else {
            this.$root.$alert(`Не получилось включить нотификации`, 'bg-warning', 3);
        }

        return true;
    },
    async onNotificationDisableClick() {

        let apiResponse = null;
        try {
            apiResponse = await this.$root.$api.$communities.unsubscribeNotify(this.community.id);
        } catch (e) {
            window.console.warn(e.detailMessage);
            this.$root.$alert(`Не получилось отключить нотификации`, 'bg-warning', 3);
            throw e;
        }
        if (apiResponse) {
            this.community.subscribed = false;
            this.$root.$alert(`Отключение нотификаций`, 'bg-success', 3);
        } else {
            this.$root.$alert(`Не получилось отключить нотификации`, 'bg-warning', 3);
        }

        return true;
    },

    onFriendInformationClick(){
        this.$root.$alert(`Рассказываю друзьям`, 'bg-info', 3);
    },
}

}
</script>
