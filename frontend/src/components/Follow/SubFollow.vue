<template>
    <small @click="unFollow" style="cursor: pointer;">
        <i class="fas fa-user-minus"></i>
    </small>
</template>

<script>
    import PliziUser from "../../classes/PliziUser.js";
    import PliziAuthUser from "../../classes/PliziAuthUser.js";

    export default {
        name: "SubFollow",
        props: {
            userData: PliziUser|PliziAuthUser,
        },
        methods: {
            async unFollow() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$users.unFollow(this.userData.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    if (apiResponse.status && apiResponse.status === 422) {
                        this.$root.$alert(apiResponse.message, 'bg-info', 3);
                    } else {
                        this.userData.stats.isFollow = false;
                        this.userData.stats.followCount = this.userData.stats.followCount - 1;
                        this.$root.$notify(apiResponse.message);
                    }
                } else {
                    this.$root.$alert(`Не получилось отписаться`, 'bg-warning', 3);
                }

                return true;

            }
        }
    }
</script>

<style scoped>

</style>
