<template>
    <span class="pl-2" v-if="!userData.stats.isFriend">
        <AddFollow :userData="userData" v-if="!userData.stats.isFollow" title="Подписаться"></AddFollow>
        <SubFollow :userData="userData" v-else-if="!userData.isOwner" title="Отписаться"></SubFollow>
        <AddToBlacklist v-if="!userData.isOwner" :userData="userData"></AddToBlacklist>
    </span>
</template>

<script>
    import PliziUser from "../../classes/PliziUser.js";
    import PliziAuthUser from "../../classes/PliziAuthUser.js";
    import SubFollow from "./SubFollow.vue";
    import AddFollow from "./AddFollow.vue";
    import AddToBlacklist from "./AddToBlacklist.vue";

    export default {
        name: "ButtonsFollow",
        components: {AddToBlacklist, AddFollow, SubFollow},
        props: {
            userData: PliziUser | PliziAuthUser,
        },
        methods: {
        },
    }
</script>
