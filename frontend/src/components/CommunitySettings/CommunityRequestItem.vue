<template>
    <li class="plizi-friend-item-user media m-0 py-4 px-4">

        <div class="plizi-friend-item d-flex w-100 align-items-center">
            <router-link :to="`/user-`+friend.id" tag="div" class="plizi-friend-item-pic mr-3 ">
                <img class="plizi-friend-item-img rounded-circle overflow-hidden"
                     v-bind:src="friend.userPic" v-bind:alt="friend.fullName"/>

                <span v-if="friend.isOnline" class="plizi-friend-item-isonline" title="онлайн"></span>
                <span v-else class="plizi-friend-item-isoffline"></span>
            </router-link>

            <div class="plizi-friend-item-body m-0 ">
                <router-link :to="`/user-`+friend.id" tag="div"
                             class="plizi-friend-item-top d-flex align-items-end justify-content-between mb-2">
                    <h6 class="plizi-friend-item-name my-0">{{ friend.fullName }}</h6>
                </router-link>

                <div class="plizi-friend-item-body-bottom d-flex ">
                    <p class="plizi-friend-item-desc p-0 my-0 d-inline">

                        <IconLocation style="height: 14px;"/>

                        <span v-if="friend.location && friend.city.title && friend.country.title">
                            {{ friend.city.title.ru +', '+  friend.country.title.ru }}
                        </span>
                        <span v-else>
                            Не указано
                        </span>
                    </p>
                </div>
            </div>

            <button @click.prevent="goToDialogWithFriend()" type="button" class="btn btn-link ml-auto"
                    :title="`Перейти к диалогу с ${friend.fullName}`">
                <IconMessageShort/>
            </button>

            <button @click.prevent="acceptRequest()" type="button" class="btn btn-link ml-0"
                    title="Принять запрос">
                <IconUserPlus  style="height: 20px;" />
            </button>

            <button @click.prevent="rejectRequest()" type="button" class="btn btn-link ml-0"
                    title="Отклонить запрос">
                <IconUserX  style="height: 20px;" />
            </button>

        </div>
    </li>
</template>

<script>
    import PliziCommunityRequest from "../../classes/Community/PliziCommunityRequest.js";
    import IconMessageShort from "../../icons/IconMessageShort.vue";
    import IconLocation from "../../icons/IconLocation.vue";
    import DialogMixin from "../../mixins/DialogMixin.js";
    import IconUserX from "../../icons/IconUserX.vue";
    import IconAddUser from "../../icons/IconAddUser.vue";
    import IconUserPlus from "../../icons/IconUserPlus.vue";

    export default {
        name: 'CommunityRequestItem',
        components: {IconUserPlus, IconAddUser, IconUserX, IconMessageShort, IconLocation},
        mixins: [DialogMixin],
        props: {
            request: PliziCommunityRequest,
            communityId: Number,
        },
        data() {
            return {};
        },
        computed: {
            friend() {
                return this.request?.user || {};
            },
        },
        methods: {
            async goToDialogWithFriend() {
                await this.openDialogWithFriend(this.friend);
                this.$root.$router.push('/chats');
            },
            async acceptRequest() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$communities.requestAccept(this.communityId, this.request.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }
                if (apiResponse) {
                    this.$emit('accepted');
                    this.$root.$notify(apiResponse.message);
                } else {
                    this.$root.$alert(`Ошибка отправки запроса`, 'bg-warning', 3);
                }
            },
            async rejectRequest() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$communities.requestReject(this.communityId, this.request.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }
                if (apiResponse) {
                    this.$emit('rejected');
                    this.$root.$notify(apiResponse.message);
                } else {
                    this.$root.$alert(`Ошибка отправки запроса`, 'bg-warning', 3);
                }
            },
        },
    }
</script>
