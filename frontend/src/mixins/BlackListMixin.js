const BlackListMixin = {
data(){
    return {
        isAddedToBlacklist: false,
    }
},
methods: {
    async addToBlacklist() {
        if (this.isAddedToBlacklist) {
            this.$root.$notify(`Пользователь ${this.userData.firstName} ${this.userData.lastName} уже внесен в чёрный список`, 'bg-warning', 3);
            return true;
        }

        let apiResponse = null;
        try {
            apiResponse = await this.$root.$api.$users.blacklistAdd(this.userData.id);
        } catch (e) {
            if (e.status === 422) {
                (console.log('выбранный пользователь уже добавлен в Ваш чёрный список'));
                this.isAddedToBlacklist = true;
                return;
            }
            window.console.warn(e.detailMessage);
        }

        this.isAddedToBlacklist = true;
        this.$root.$notify(`Вы добавили пользователя ${this.userData.firstName} ${this.userData.lastName} в чёрный список`);
    },
    async deleteFromBlacklist(userId) {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$users.blacklistDelete(userId);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        this.isAddedToBlacklist = false;
        this.$root.$notify(`Вы удалили пользователя ${this.userData.firstName} ${this.userData.lastName} из чёрного списка`);
    }
}

};

export {BlackListMixin as default}
