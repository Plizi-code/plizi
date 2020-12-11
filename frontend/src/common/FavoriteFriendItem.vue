<template>
    <div class="plz-favorit-friends-item position-relative"
         :class="{ 'active': chatWindowShown }"
         @click.prevent="showRelatedChat($event)">

        <div class="plz-favorit-friends-item-wrap d-flex align-items-center align-items-center py-2 px-3 ">

            <div class="plz-favorit-friend-userpic"  :class="{'mx-auto' : isNarrow}">
                <img class="plz-favorit-userpic rounded-circle" :src="friend.userPic" :alt="friend.firstName" />

                <div v-if="isTyper" class="writing"><span></span><span></span><span></span></div>
                <div v-else class="">
                    <span v-if="friend.isOnline" class="plz-favorit-isonline" title="в сети"></span>
                    <span v-else class="plz-favorit-isoffline" title="не в сети"></span>
                </div>
            </div>

            <div v-if="!isNarrow" class="plz-favorit-friend-title flex align-items-center mr-auto">
                <span class="plz-favorit-friend-name">{{friend.fullName}}</span>

                <div class="plz-favorit-friend-status">
                    <p v-if="friend.isOnline" class="plz-favorit-friend-status-online">В сети</p>
                    <p v-else class="plz-favorit-friend-status-last-activity">{{ favoriteLastActivity }}</p>
                </div>
            </div>

            <span v-if="!isNarrow" class="plz-ff-messages-count">
                <span v-if="(friend.messagesNumber > 0)" class="plz-ff-messages-count-number py-0 mr-2">
                    {{friend.messagesNumber}}
                </span>
            </span>
        </div>

        <div v-if="isShowLinkedChat" class="plz-linked-chat-block mr-3 bg-white-br20 "
             :class="{ 'active-chat plz-favorit-z': chatWindowShown }">

            <!--TODO @TRG class="is-pinned" когда чат прибит-->
            <div id="chatMessagesWrapper" class="plz-linked-chat-body bg-light d-none d-lg-flex flex-column p-0"
                 :class="{'is-pinned' : 'когда_чат_прибит'}">

                <ChatLinkedHeader v-if="currentDialog" v-bind:currentDialog="currentDialog"
                            @ChatMessagesFilter="onUpdateMessagesFilterText"
                                  @CloseLinkedChat="onCloseLinkedChat"
                            ref="chatHeader">
                </ChatLinkedHeader>

                <div id="chatMessagesWrapperBody" class="position-relative">
                    <div v-if="isMessagesLoaded"  class="plz-gallery-attached-chat">
                        <ChatMessages v-bind:messagesList="messagesList"
                                      v-bind:filter="filter"
                                      v-bind:currentDialog="currentDialog"
                                      :style="`padding-bottom: ${changedHeight}`"
                                      @clearFilters="clearChatMessagesFilters"
                                      ref="chatMessages">
                        </ChatMessages>
                    </div>
                    <Spinner v-else v-bind:message="`Сообщения загружаются`"></Spinner>

                    <ChatFooter v-if="currentDialog"
                                v-bind:currentDialog="currentDialog"
                                @chatFooterEditorChangedHeight="onChatFooterEditorChangedHeight"
                                :style="`height: ${changedHeight}`"
                                ref="ChatFooter">
                    </ChatFooter>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from '../common/Spinner.vue';

import ChatLinkedHeader from '../common/Chat/ChatLinkedHeader.vue';
import ChatMessages from '../common/Chat/ChatMessages.vue';
import ChatFooter from '../common/Chat/ChatFooter.vue';

import FriendItemMixin from '../mixins/FriendItemMixin.js';
import DialogMixin from '../mixins/DialogMixin.js';

import PliziFriend from '../classes/PliziFriend.js';
import PliziMessage from '../classes/PliziMessage.js';
import PliziMessagesCollection from '../classes/Collection/PliziMessagesCollection.js';

export default {
name : 'FavoriteFriendItem',
components: {
    Spinner,
    ChatLinkedHeader, ChatMessages, ChatFooter,
},
mixins : [FriendItemMixin, DialogMixin],
props : {
    friend : {
        type: PliziFriend,
        required: true,
    },
    pickedFavoriteId: {
        type: String,
        required: false,
        default: 'none'
    },
    isNarrow: {
        type: Boolean,
        required: false,
        default: false
    }
},

data(){
    return {
        isShowLinkedChat: false,
        typingTimeout: null,
        isTyper: false,

        currentDialog : null,
        messagesList  : (new PliziMessagesCollection()),
        isMessagesLoaded: false,

        filter : {
            text: '',
            range: null,
        },

        dialogsSearchedList: null,
        changedHeight: '',
    }
},

computed: {
    chatWindowShown(){
        return this.friend.id === this.pickedFavoriteId;
    },

    favoriteLastActivity(){
        return this.lastFriendActivity(this.friend);
    }
},

methods: {
    onUpdateMessagesFilterText(evData){
        this.filter.text = evData.text ? evData.text.trim() : '';
        this.filter.range = evData.range && evData.range.start && evData.range.end ? evData.range : null;
        this.$forceUpdate();
    },

    onCloseLinkedChat(){
        this.isShowLinkedChat = false;
        this.$emit('UnPickFavorite', { friendId : this.friend.id });
    },

    clearChatMessagesFilters() {
        // TODO: нужна прокрутка вниз
        this.$refs.chatHeader.clearFilters();

        this.onUpdateMessagesFilterText({
            text : '',
            range: null
        });
    },

    onChatFooterEditorChangedHeight(evData) {
        this.changedHeight = evData.changedHeight + 'px';
        try{
            this.$refs.chatMessages.scrollToEnd();
        } catch (e){

        }
    },


    addMessageToMessagesList(evData){
        if (! this.isShowLinkedChat)
            return;

        this.currentDialog = this.$root.$auth.dm.getDialogByCompanion(this.friend.id);

        if (this.currentDialog) {
            if (this.currentDialog.id === evData.message.chatId){

                this.messagesList.append( evData.message );

                if (this.$refs && this.$refs.chatMessages) {
                    this.$refs.chatMessages.$forceUpdate();
                    this.$refs.chatMessages.scrollToEnd();
                }
            }
        }
        else {
            window.console.warn('нулёвый диалог');
        }
    },


    async showRelatedChat(ev){
        /** @TGA надо придумать способ проверки поумнее **/
        if (! this.isSwitchedClass(ev.target.className) )
            return;

        const evData = { friendId : this.friend.id };

        if (this.chatWindowShown) {
            this.isShowLinkedChat = false;
            this.$emit('UnPickFavorite', evData);
        }
        else {
            this.$emit('PickFavorite', evData);
            this.currentDialog = this.$root.$auth.dm.getDialogByCompanion(this.friend.id);
            if (this.currentDialog) {
                await this.chatSelect( this.currentDialog.id );
                this.isShowLinkedChat = true;
            }
            else {
                await this.openDialogWithFriend( this.friend );

                this.currentDialog = this.$root.$auth.dm.getDialogByCompanion(this.friend.id);
                if (this.currentDialog) {
                    await this.chatSelect( this.currentDialog.id );
                    this.isShowLinkedChat = true;
                }
                this.isShowLinkedChat = true;
            }
        }
    },


    isSwitchedClass(className){
        const classesList = [
            'plz-favorit-friends-item d-flex align-items-center align-items-center py-2 px-3 position-relative',
            'plz-favorit-friends-item d-flex align-items-center align-items-center py-2 px-3 position-relative active',
            'plz-favorit-friend-userpic',
            'plz-favorit-userpic rounded-circle',
            'plz-favorit-isonline',
            'plz-favorit-isoffline',
            'plz-favorit-friend-title flex align-items-center mr-auto',
            'plz-favorit-friend-name',
            'plz-favorit-friend-status',
            'plz-favorit-friend-status-online',
            'plz-favorit-friend-status-last-activity',
            'plz-ff-messages-count',
            'plz-ff-messages-count-number py-0 mr-2'
        ];

        return classesList.includes(className);
    },


    /**
     * @param {Object} evData
     */
    onFriendTyping(evData) {
        if (this.friend.id !== evData.user.id)
            return;

        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        this.isTyper = true;

        this.typingTimeout = setTimeout(() => {
            this.isTyper = false;
        }, 3000);
    },


    removeMessageInLinkedChat(evData){
        if (! this.isShowLinkedChat)
            return;

        this.currentDialog = this.$root.$auth.dm.getDialogByCompanion(this.friend.id);

        if (this.currentDialog) {
            if (this.currentDialog.id !== evData.chatId)
                return;

            this.messagesList.delete(evData.messageId);
            this.updateDialogsList(evData.chatId, { message: this.messagesList.last });
        }
    },


    updateDialogsList(chatId, evData){
        evData.chatId = chatId;

        this.$root.$emit('UpdateChatDialog', evData);
    },


    async chatSelect(chatId){
        let msgsResponse = null;
        this.isMessagesLoaded = false;

        this.currentDialog = this.$root.$auth.dm.get(chatId);

        try {
            msgsResponse = await this.$root.$api.$chat.messages(chatId);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        window.localStorage.setItem('pliziActiveDialog', chatId);

        this.messagesList.clear();
        msgsResponse.map( (msg) => {
            this.messagesList.append( msg );
        });

        this.isMessagesLoaded = true;
    },


    addListeners(){
        this.$root.$on('userIsTyping', this.onFriendTyping);

        this.$root.$on('newMessageInDialog', this.addMessageToMessagesList);
        this.$root.$on('removeMessageInDialog', this.removeMessageInLinkedChat);
    }
},

created(){
    this.addListeners();
}

}
</script>
