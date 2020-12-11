<template>
    <div class="chat-dialogs-list col-sm-12 col-md-12 col-lg-4 col-xl-4 col-auto px-0 d-flex flex-column align-items-stretch ">

        <ChatDialogsFilter @ChatDialogsFilter="onChatDialogsFilter"></ChatDialogsFilter>
        <div class="chat-list-scroll pb-0 pb-lg-0">
            <vue-custom-scrollbar class="chat-list-scroll-scroll w-100"
                                  :settings="customScrollBarSettings">
                <ul id="chatDialogsList" class="list-unstyled mb-0">
                    <ChatListItem v-for="dialog in dialogsList()"
                                  @PickChat="onSwitchToChat"
                                  :id="'dialogItem-'+dialog.id"
                                  v-bind:dialog="dialog"
                                  v-bind:currentDialogID="currentDialogID"
                                  v-bind:key="dialog.id+`-`+$root.$dialogsKeyUpdater+`-`+$root.$messagesKeyUpdater">
                    </ChatListItem>
                </ul>
            </vue-custom-scrollbar>
        </div>
    </div>
</template>

<script>
import VueCustomScrollbar from 'vue-custom-scrollbar';

import ChatDialogsFilter from './ChatDialogsFilter.vue';
import ChatListItem from './ChatListItem.vue';

import PliziDialog from '../../classes/PliziDialog.js';

export default {
name : 'ChatDialogs',
components : { ChatDialogsFilter, ChatListItem, VueCustomScrollbar },
props : {
    currentDialogID : String
},

data(){
    return {
        listFilled: false,
        dialogKeyUpdater: 0,

        dialogFilter: {
            text: ``,
        },

        customScrollBarSettings: {
            maxScrollbarLength: 60,
            useBothWheelAxes: false,
            suppressScrollX: true,
            wheelPropagation: false
        }
    }
},

computed: {
    isMessagesAutoLoad(){
        return (this.$root.$isLG()  || this.$root.$isXL())
    }
},

methods: {
    dialogsList(){
        const dlgList = this.$root.$auth.dm.asArray();

        if ( this.dialogFilter.text.length < 3 )
            return dlgList;

        return dlgList.filter(dlgItem => dlgItem.checkInAttendees(this.dialogFilter.text) );
    },

    onSwitchToChat(evData){
        this.$emit('SwitchToChat', evData);
    },

    onChatDialogsFilter(evData){
        this.dialogFilter.text = evData.text ? evData.text.trim() : '';
    },

    onRemoveChatDialog(evData){
        this.$root.$auth.dm.onRemoveDialog( evData.chatId );
        this.$root.$dialogsKeyUpdater++;

        if (this.$root.$auth.dm.size > 0) {
            const newPickedChat = this.$root.$auth.dm.firstDialog.id;

            this.$emit('SwitchToChat', { chatId: newPickedChat });
        }
        else {
            window.console.info(`нет диалогов`);
        }
    },

    onUpdateChatDialog(evData) {
        let messageBody = evData.message.body;

        if (evData.message.body === ''  ||  evData.message.body==='<p></p>') {
            let id  = this.$root.$messagesList.last.id;
            let msg = this.$root.$messagesList.get(id);

            let node = this.$root.$messagesList.nodes.get(id);

            while(true){
                if (node.prev === null){
                    break;
                }

                id = node.self;
                node = this.$root.$messagesList.nodes.get(node.prev);
                msg = this.$root.$messagesList.get(id);
                if (msg.body !== '') {
                    break;
                }
            }

            messageBody = msg.body;
        }

        const updatedFields = {
            lastMessageDT : evData.message.createdAt,
            lastMessageText : messageBody,
            isLastFromMe : !!evData.message.isMine,
            isRead : !!evData.message.isRead,

            userId: evData.message.userId
        };

        this.$root.$auth.dm.dialogStateUpdated(evData.chatId, updatedFields);
        this.$root.$messagesKeyUpdater++;
    },

    remoteCreateDialog(evData){
        let newDlg = new PliziDialog(evData.data);
        newDlg.removeAttendee( this.$root.$auth.user.id );

        this.$root.$auth.dm.onAddNewDialog( newDlg.toJSON() );
        this.$root.$dialogsKeyUpdater++;
    },

    remoteAddAttendee(evData){
        return this.remoteCreateDialog(evData);
    },

    remoteRemoveAttendee(evData){
        if (evData.userId === this.$root.$auth.user.id) {
            this.onRemoveChatDialog(evData);
        }
    },

    async loadDialogsList() {
        this.isDialogsLoaded = true;

        const lastDialogID = window.localStorage.getItem('pliziActiveDialog');
        this.currentDialog = this.$root.$auth.dm.get(lastDialogID);

        if (typeof this.currentDialog === 'undefined') {
            this.currentDialog = this.$root.$auth.dm.firstDialog;
        }

        if (this.currentDialog) {
            window.localStorage.setItem('pliziActiveDialog', this.currentDialog.id);
        }

        return true;
    },


    async onDialogsListLoad(wMode){
        if (this.listFilled)
            return;

        this.dialogKeyUpdater++;

        await this.loadDialogsList();

        this.listFilled = true;

        if ( this.currentDialog ) {
            if ( this.isMessagesAutoLoad) {
                this.onSwitchToChat( { chatId : this.currentDialog.id })
            }
        }
        else {
            window.console.warn(`Условие не сработало!`);
        }
    }

},

created() {
    this.$root.$on(this.$root.$auth.dm.loadEventName, ()=>{
        this.onDialogsListLoad(this.$root.$auth.dm.loadEventName);
    });

    this.$root.$on(this.$root.$auth.dm.restoreEventName, ()=>{
        this.onDialogsListLoad(this.$root.$auth.dm.restoreEventName);
    });

    this.$root.$on('RemoveChatDialog', this.onRemoveChatDialog);
    this.$root.$on('UpdateChatDialog', this.onUpdateChatDialog);

    // эвенты через ВебСокеты
    this.$root.$on('remoteCreateDialog', this.remoteCreateDialog);
    this.$root.$on('remoteRemoveDialog', this.onRemoveChatDialog);
    this.$root.$on('remoteAddAttendee',  this.remoteAddAttendee);
    this.$root.$on('remoteRemoveAttendee',  this.remoteRemoveAttendee);
},


beforeDestroy() {
    this.$root.$off(this.$root.$auth.dm.loadEventName, ()=>{
        this.onDialogsListLoad(this.$root.$auth.dm.loadEventName);
    });

    this.$root.$off(this.$root.$auth.dm.restoreEventName, ()=>{
        this.onDialogsListLoad(this.$root.$auth.dm.restoreEventName);
    });

    this.$root.$off('RemoveChatDialog', this.onRemoveChatDialog);
    this.$root.$off('UpdateChatDialog', this.onUpdateChatDialog);
},


async mounted(){
    if (! this.listFilled) {
        await this.onDialogsListLoad(`mounted`);
    }
}

}
</script>
