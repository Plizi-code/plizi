<template>
    <div id="recommendedCommunities" class="plz-potential-friends" :class="clazz">

        <div v-if="title" class="d-flex flex-row justify-content-start pb-3 --pt-5">
            <h6 class="plz-sf-title w-auto mt-3 ml-3">{{ title }}</h6>
        </div>

        <div class="plz-recommended-communities-list pb-2">
               <CommunitiesSmallBlockItem v-for="community in communities"
                                :key="community.id"
                                :community="community"/>

        </div>

        <div v-if="footer" class="d-flex flex-row footer-link justify-content-start pb-3 --pt-5 border-top">
            <router-link :to="footer.path" tag="a" class="ml-4 mt-2">{{ footer.title }}</router-link>
        </div>
    </div>
</template>

<script>
    import CommunitiesSmallBlockItem from "./CommunitiesSmallBlockItem.vue";

    export default {
        name: 'CommunitiesSmallBlock',
        components: {CommunitiesSmallBlockItem},
        props: {
          communities: Array,
            title: String,
            footer: Object,
            clazz: String
        },
        data() {
            return {
            };
        },
    }
</script>
