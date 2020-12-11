<template>
    <a class="text-body" @click.stop="doClick" title="Снять полномочия администратора" >
        <IconAdminRemove  style="height: 22px"/>
    </a>
</template>

<script>
import PliziMember from "../../classes/PliziMember.js";
import IconAdminRemove from "../../icons/IconAdminRemove.vue";

export default {
name: "ButtonsStopBeAdmin",
components: {IconAdminRemove},
props: {
    srItem: PliziMember,
    communityId: Number,
},
methods: {
    async doClick() {
        let apiResponse;

        try {
            apiResponse = await this.$root.$api.$communities.stopBeAdmin(this.communityId, this.srItem.id);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.srItem.role = 'user';
            this.$root.$notify(`Пользователь ${this.srItem.firstName} ${this.srItem.lastName} удален из администраторов сообщества`);
        }
    }
},
}
</script>
