<template>
    <div class="activeSessionsPage container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-xl-9 px-0 px-md-3">
                <div id="activeSessions" class="container-fluid bg-white-br20 plz-mb20">
                    <h4 class="py-3">Активные сессии</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-baseline mb-3">
                                <button class="btn btn-primary mr-3"
                                        @click.prevent="closeActiveSessions">
                                    Завершить все сеансы кроме текущего
                                </button>
                                <p v-if="isSuccess"
                                   class="text-success">
                                    Сеансы были успешно завершены.
                                </p>
                            </div>
                            <ul v-if="activeSessions && activeSessions.length" class="list-group mb-3">
                                <li v-for="(activeSession, index) in activeSessions"
                                    :key="index"
                                    class="list-group-item">
                                    <span>
                                       {{ activeSession.createdAt * 1000 | lastEventTime }}
                                        - браузер {{ activeSession.userAgent }}.
                                        IP адрес: {{ activeSession.ip }}
                                    </span>

                                    <span v-if="activeSession.isCurrent"
                                          class="text-primary">
                                        - Текущая сессия.
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0  px-0 px-md-3">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
    import FavoriteFriends from '../common/FavoriteFriends.vue';

    export default {
        name: "ActiveSessionsPage",
        components: {
            AccountToolbarLeft,
            FavoriteFriends,
        },
        data() {
            return {
                activeSessions: [],
                isSuccess: false,
            }
        },
        methods: {
            async getActiveSessions() {
                let response;

                try {
                    response = await this.$root.$api.$users.getActiveSessions();
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.activeSessions = response;
                }
            },
            async closeActiveSessions() {
                let response;

                try {
                    response = await this.$root.$api.$users.closeActiveSessions();
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.isSuccess = true;

                    setTimeout(() => {
                        this.isSuccess = false;
                    }, 3000);
                }
            },
        },
        async mounted() {
            await this.getActiveSessions();
        },
    }
</script>

<style lang="scss"></style>
