<template>
    <small @click="follow" style="cursor: pointer;">
        <i class="fas fa-user-plus"></i>
    </small>
</template>

<script>
    import PliziUser from "../../classes/PliziUser.js";
    import PliziAuthUser from "../../classes/PliziAuthUser.js";

    export default {
        name: "AddFollow",
        props: {
            userData: PliziUser|PliziAuthUser,
        },
        methods: {
            async follow() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$users.follow(this.userData.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    if (apiResponse.status && apiResponse.status === 422) {
                        this.$root.$alert(apiResponse.message, 'bg-info', 3);
                    } else {
                        this.userData.stats.isFollow = true;
                        this.userData.stats.followCount = this.userData.stats.followCount + 1;
                        this.$root.$notify(apiResponse.message);
                    }
                } else {
                    this.$root.$alert(`Не получилось подписаться`, 'bg-warning', 3);
                }

                return true;

            }
        }
    }
</script>

