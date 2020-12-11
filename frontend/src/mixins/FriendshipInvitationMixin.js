const FriendshipInvitationMixin = {

methods: {
    async sendFriendshipInvitation(id, fullName) {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$friend.sendFriendshipInvitation(id);
        } catch (e) {
            window.console.warn(e.detailMessage);
            return;
        }

        if (apiResponse !== null) {
            if (apiResponse.status === 200) {
                this.$notify(`Приглашение дружбы для <b class="friend-name">${fullName}</b> отправлено!`);
            }

            if (apiResponse.status === 422) {
                this.$notify(apiResponse.message);
            }
        }
    },

    async stopFriendship(friendId){
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$friend.friendshipStop( friendId );
        } catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        this.isPrepareToRemoved = true;

        if ( apiResponse ) {
            this.isRemoved = true;

            const exFriend = this.$root.$auth.frm.get(friendId).toJSON();
            this.$root.$auth.fm.removeFromFavorites(friendId);
            this.$root.$auth.frm.stopFriendship(friendId);

            this.$root.$auth.friendsDecrease();

            this.$root.$friendsKeyUpdater--;
            this.$root.$favoritesKeyUpdater--;

            this.$emit( 'FriendshipStop', {
                friendId: friendId
            });
            this.$notify(`<b class="friend-name">${exFriend.profile.firstName} ${exFriend.profile.lastName}</b> больше не Ваш друг`);
        }
    }
}

};

export {FriendshipInvitationMixin as default}
