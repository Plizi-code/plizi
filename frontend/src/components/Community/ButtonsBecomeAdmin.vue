<template>
    <a class="text-body" @click.stop="doClick" title="Назначить администратором">
        <IconAdminAdd style="height: 22px; "/>
    </a>
</template>

<script>
import IconAdminAdd from "../../icons/IconAdminAdd.vue";

import PliziMember from "../../classes/PliziMember.js";

export default {
name: "ButtonsBecomeAdmin",
    components: {IconAdminAdd},
    props: {
    srItem: PliziMember,
    communityId: Number,
},
methods: {
    async doClick() {
        let apiResponse;

        try {
            apiResponse = await this.$root.$api.$communities.becomeAdmin(this.communityId, this.srItem.id);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.srItem.role = 'admin';
            this.$root.$notify(`Пользователь ${this.srItem.firstName} ${this.srItem.lastName} назначен администратором сообщества`);
        }
    }
},
}
</script>
