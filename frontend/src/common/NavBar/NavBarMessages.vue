<template>
    <div class="plz-top-watcher-item position-relative d-inline-block  mr-0 mr-sm-2">
        <router-link to="/chats" tag="a" class="btn btn-link my-auto text-body btn-sm" @click.native="onGoToChat">
            <IconMessageShort />
        </router-link>

        <span v-if="messagesNumber>0" class="counter-info" id="dropdownMenuMessages"
              type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{messagesNumber}}
        </span>

        <div v-if="messagesNumber>0"
            class="dropdown-menu dropdown-menu-right pt-3 pb-0  dropdown-white w-auto"
                aria-labelledby="dropdownMenuMessages">

            <ul class="list-unstyled mb-0">
                <MessageNotificationItem v-for="(msgItem, msgIndex) in messagesList"
                          v-bind:key="msgIndex" v-bind:message="msgItem"></MessageNotificationItem>
            </ul>
            <div class="notifications-likes-dropdown-footer border-top">
                <a href="#" class="notifications-link d-block text-center pt-1 pb-3">Посмотреть все</a>
            </div>
        </div>
    </div>
</template>

<script>
import IconMessage from '../../icons/IconMessage.vue';
import MessageNotificationItem from '../../components/MessageNotificationItem.vue';
import IconMessageShort from '../../icons/IconMessageShort.vue';

export default {
name : 'NavBarMessages',
components : {IconMessageShort, IconMessage, MessageNotificationItem },

data(){
    return {
        messagesNumber : 0,
        messagesLimit : 10
    }
},

computed: {
    messagesList(){
        let messages = this.$root.$auth.dialogs.filter((dItem)=>{
            return (!dItem.isLastFromMe  && !dItem.isRead);
        });

        return messages.slice(0, this.messagesLimit);
    }
},

methods : {
    onGoToChat(){
        this.$root.$emit('GoToChat', {});
        return true;
    },

    updateMessages(){
        this.messagesNumber = 0;

        this.$root.$auth.dialogs.map( (dItem) => {
            if (!dItem.isLastFromMe  && !dItem.isRead) {
                this.messagesNumber++;
            }
        });

        this.$forceUpdate();
    }
},

created(){
    /** @TGA ошибки нет - после загрузки списка мы считаем количество  **/
    this.$root.$on('dialogsLoad',  this.updateMessages);
}
}
</script>
