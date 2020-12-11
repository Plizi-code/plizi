<template>
    <li class="chat-list-user media m-0 px-4 py-2"
        :class="{ 'bg-light':  dialog.id === currentDialogID, 'bg-white': dialog.id !== currentDialogID  }">

        <div class="user-friend d-flex w-100 px-0 --overflow-hidden" @click.prevent="pickChat()">

            <div v-if="dialog.isPrivate" class="user-friend-pic mr-3">
                <img class="user-friend-img rounded-circle overflow-hidden"
                     :src="dialog.companion.userPic" :alt="dialog.companion.fullName" />

                <div v-if="isTyper" class="writing"><span></span><span></span><span></span></div>
                <div v-else class="">
                    <span v-if="dialog.companion.isOnline" class="user-friend-isonline"
                          :title="dialog.companion.firstName + ' онлайн'"></span>
                    <span v-else class="user-friend-isoffline"></span>
                </div>
            </div>

<!--            <template v-if="dialog.isGroup"> &lt;!&ndash; TODO: @TGA удалить когда Сергей сделает нормально &ndash;&gt;-->
<!--                <div class="user-friend-pic  mr-3">-->
<!--                    <img class="user-friend-img rounded-circle overflow-hidden"-->
<!--                         :src="dialog.companion.userPic" :alt="dialog.companion.fullName" />-->
<!--                </div>-->
<!--            </template>-->

            <template v-if="dialog.isGroup"> <!-- @TGA на самом деле "dialog.isGroup" -->
                <div class="is-group-chat position-relative mr-3">
                    <div v-for="attende in threeAttendess()" class="user-friend-pic" >
                        <img
                             :src="attende.userPic"
                             :alt="attende.fullName"
                             :key="attende.id"
                             class="user-friend-img rounded-circle overflow-hidden" />
                    </div>
                </div>
            </template>

            <div class="user-friend-body flex-fill m-0">
                <div class="user-friend-body-top d-flex align-items-end justify-content-between">
                    <h6 v-if="dialog.isPrivate" class="user-friend-name my-0">{{ dialog.companion.fullName }}</h6>
                    <h6 v-if="dialog.isGroup" class="user-friend-name my-0 group-chat-name">{{ groupDialogName }}</h6>

                    <small v-if="dialog.isRead " class="mr-1 ml-auto mb-1">
                        <IconCheckedDouble :clazz="'mb-2'" />
                    </small>
                    <time :datetime="lastMessageDT" class="">
                        {{ showDialogTime }}
                    </time>
                </div>

                <div class="user-friend-body-bottom d-flex">
                    <span v-if="isLastFromMe" class="user-friend-details mr-1">Вы:</span>
                    <p class="user-friend-desc p-0 my-0 d-inline" v-html="lastMsgText"></p>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import IconCheckedDouble from '../../icons/IconCheckedDouble.vue';
import PliziDialog from '../../classes/PliziDialog.js';

export default {
name: 'ChatListItem',
components: { IconCheckedDouble},
props: {
    dialog: {
        type: PliziDialog,
        required : true
    },
    currentDialogID: {
        type: String,
        required: true,
        default: 'unknown'
    }
},

data(){
    return {
        typingTimeout: null,
        isTyper: false,
        lastMessageText : this.dialog.lastMessageText,
        lastMessageDT: this.dialog.lastMessageDT,
        isLastFromMe: this.dialog.isLastFromMe
    }
},

computed: {
    lastMsgText(){
        if (!this.lastMessageText) {
            return `<span class="text-black-50">вы ещё ничего на написали друг другу</span>`;
        }

        let txt = this.lastMessageText || ``;
        txt = txt.replace(/<br\/>/g, '&nbsp;');

        return this.$options.filters.stripTags(txt);
    },

    groupDialogName(){
        if (this.dialog.name)
            return this.dialog.name;

        return 'Вы, '+ this.dialog.attendeesName.join(', ');
    },

    showDialogTime(){
        if (this.lastMessageDT===null  ||  this.lastMessageDT.valueOf()===0)
            return '';

        return this.$options.filters.lastMessageTime(this.lastMessageDT);
    }
},

methods: {
    threeAttendess(){
        return this.dialog.attendees.slice(0, 2);
    },

    pickChat() {
        this.$emit('PickChat', {chatId: this.dialog.id});
    },

    /**
     * @param {Object} evData
     */
    onCompanionTyping(evData) {
        if (this.dialog.companion.id !== evData.user.id)
            return;

        if (this.dialog.id !== evData.chatId)
            return;

        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        this.isTyper = true;

        this.typingTimeout = setTimeout(() => {
            this.isTyper = false;
        }, 1000);
    }
},

mounted(){
    this.$root.$on('DialogsIsUpdated', (evData)=>{
        /** FIXME: TGA это костыль - нужно добиться реактивности данных нативными средствами **/
        if (evData.id === this.dialog.id) {
            this.lastMessageText = evData.lastMessageText;
            this.lastMessageDT = evData.lastMessageDT;
            this.isLastFromMe = evData.isLastFromMe;

            this.$forceUpdate();
        }
    });

    this.$root.$on('userIsTyping', this.onCompanionTyping);
}

}
</script>
