import PliziFriend from '../classes/PliziFriend.js';

const DialogMixin = {

methods: {

    /**
     * делает переход к диалогу с юзером
     * @param {PliziFriend} user -
     * @returns
     */
    async openDialogWithFriend(user){
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$chat.dialogOpen( user.fullName,[user.id] );
        }
        catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ) {
            window.localStorage.setItem('pliziActiveDialog', apiResponse.id);
            this.$root.$emit('NewChatDialog', apiResponse);
        }
    },


    /**
     * создаёт групповой чат с админами сообщества
     * @param {string} dialogName - название диалога
     * @param {string[]} adminsIds - ID-шники админов
     * @param {string} userId - ID жалобщика
     */
    async openDialogWithAdmins(dialogName, adminsIds, userId){
        let apiResponse = null;

        let usersIds = adminsIds.slice();
        usersIds.push(userId);

        try {
            apiResponse = await this.$root.$api.$chat.dialogOpen(dialogName, usersIds);
        }
        catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ) {
            window.localStorage.setItem('pliziActiveDialog', apiResponse.id);
            this.$root.$emit('NewChatDialog', apiResponse);
        }
    },
}

};

export {DialogMixin as default}
