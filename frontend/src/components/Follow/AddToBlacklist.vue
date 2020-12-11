<template>
    <small @click="addToBlacklist" class="cursor-pointer ml-2" title="Добавить в чёрный список">
        <i v-if="isAddedToBlacklist === false" class="fas fa-user-slash text-danger"></i>
        <i v-else class="fas fa-user-slash text-black-50"></i>
    </small>
</template>

<script>
    import PliziUser from "../../classes/PliziUser.js";

    export default {
        name: "AddToBlacklist",
        props: {
            userData: PliziUser,
        },
        data() {
            return {
                isAddedToBlacklist: false,
            }
        },
        methods: {
            async getBlacklist() {
                let apiResponse = null;
                let res = null;

                try {
                    apiResponse = await this.$root.$api.$users.blacklistGet();
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                res = apiResponse.filter(user => user.id === this.userData.id);

                if (res.length) {
                    this.isAddedToBlacklist = true;
                    console.log('user is in the blacklist');
                }
            },
            async addToBlacklist() {
                if (this.isAddedToBlacklist === false) {
                    let apiResponse = null;

                        try {
                            apiResponse = await this.$root.$api.$users.blacklistAdd(this.userData.id);
                        } catch (e) {
                            if (e.status === 422) {
                                (console.log('выбранный пользователь уже добавлен в ваш черный список'));
                                this.isAddedToBlacklist = true;
                                return;
                            }
                            window.console.warn(e.detailMessage);
                        }

                    this.isAddedToBlacklist = true;
                    this.$root.$alert(`Вы добавили пользователя в черный список`, 'bg-success', 3);
                } else {
                    this.$root.$alert(`Пользователь уже внесен в черный список`, 'bg-warning', 3);
                } return true;
            }

        },
        async mounted() {
            await this.getBlacklist();
        },
    }
</script>

<style scoped>

</style>
