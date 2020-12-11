<template>
    <li class="plizi-friend-item-user media m-0 py-4 px-4">

        <div class="plizi-friend-item d-flex w-100 align-items-center">
            <router-link :to="`/user-`+follow.id" tag="div" class="plizi-friend-item-pic mr-3 " >
                <img class="plizi-friend-item-img rounded-circle overflow-hidden"
                     v-bind:src="follow.userPic" v-bind:alt="follow.fullName" />

                <span v-if="follow.isOnline" class="plizi-friend-item-isonline" title="онлайн"></span>
                <span v-else class="plizi-friend-item-isoffline"></span>
            </router-link>

            <div class="plizi-friend-item-body m-0 ">
                <router-link :to="`/user-`+follow.id" tag="div"
                             class="plizi-friend-item-top d-flex align-items-end justify-content-between mb-2" >
                    <h6 class="plizi-friend-item-name my-0">{{ follow.fullName }}</h6>
                </router-link>

                <div class="plizi-friend-item-body-bottom d-flex ">
                    <p class="plizi-friend-item-desc p-0 my-0 d-inline">

                        <IconLocation style="height: 14px;" />

                        <span v-if="follow.location && follow.city.title && follow.country.title">
                            {{ follow.city.title.ru +', '+  follow.country.title.ru }}
                        </span>
                        <span v-else>
                            Не указано
                        </span>
                    </p>
                </div>
            </div>

            <button @click.prevent="unFollow()" type="button" class="btn btn-link ml-auto"
                    title="Отписаться">
                <IconUserX  style="height: 20px;" />
            </button>
        </div>
    </li>
</template>

<script>
    import PliziUser from "../../classes/PliziUser.js";

    import IconUserX from "../../icons/IconUserX.vue";
    import IconLocation from "../../icons/IconLocation.vue";

    export default {
        name: 'FollowItem',
        components: {IconLocation, IconUserX},
        props: {
            follow: {
                type: PliziUser,
                required: true
            }
        },
        data() {
            return {};
        },

        methods: {
            async unFollow() {
                let response = null;

                try {
                    response = await this.$root.$api.$users.unFollow(this.follow.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (response) {
                    if (response.status && response.status === 422) {
                        this.$root.$alert(response.message, 'bg-info', 3);
                    } else {
                        this.$emit('unFollow', {
                            id: this.follow.id
                        });
                        this.$root.$notify(response.message);
                    }
                } else {
                    this.$root.$alert(`Не получилось отписаться`, 'bg-warning', 3);
                }
            },
        }

    }
</script>


