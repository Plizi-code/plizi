const ChatMixin = {

methods: {
    killBrTrail(sText){
        const brExample = `<br/>`;

        while (true){
            const pos = sText.length - brExample.length;
            const trail = sText.substr(pos).toLowerCase();

            if (trail === brExample) {
                sText = sText.substr(0, pos);
            }
            else {
                break;
            }
        }

        return sText;
    },

    async lazyLoadMessages(chatId, offset, limit){
        if (!this.isCanLoadMoreMessages)
            return;



        if (this.isMessagesLazyLoad)
            return;

        this.isMessagesLazyLoad = true;
        let msgsResponse = null;

        try {
            msgsResponse = await this.$root.$api.$chat.messages(chatId, offset, limit);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        this.isMessagesLazyLoad = false;

        window.localStorage.setItem('pliziActiveDialog', chatId);

        if (msgsResponse){
            if (msgsResponse.length > 0) {
                this.isCanLoadMoreMessages = true;
                msgsResponse.map( (msg) => {
                    this.prependMessageToMessagesList(msg);
                });
            }
            else {
                this.isCanLoadMoreMessages = false;
            }
        }
    },

    prependMessageToMessagesList(evData){
        this.messagesList.prepend( evData );
    },
}

};

export {ChatMixin as default}
