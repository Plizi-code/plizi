<template>
    <div class="plizi-search-results-list">
        <ul v-if="followsNumber>0" class="d-block w-100 p-0 pt-2">
            <FollowItem v-for="invItem in follows"
                        @unFollow="unFollow"
                        :follow="invItem"
                        :key="invItem.id">
            </FollowItem>
        </ul>
        <div class="p-3" v-else>
            <div class="alert alert-info text-center mb-0">
                Вы не подписаны ни на одного пользователя
            </div>
        </div>
    </div>
</template>

<script>
    import FollowItem from './FollowItem.vue';

    export default {
        name: 'FollowList',
        components: {FollowItem},
        props: {
            follows: Array,
            followsNumber: Number,
        },
        data() {
            return {};
        },

        methods: {
            unFollow(evData) {
                this.$emit('unFollow', evData);
            },
        }
    }
</script>
