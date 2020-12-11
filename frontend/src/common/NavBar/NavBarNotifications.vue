<template>
    <div class="plz-top-watcher-item position-relative d-inline-block mr-0 mr-sm-2">
        <div class="btn btn-link my-auto text-body btn-sm cursor-pointer" title="Уведомления" ref="dropdown">
            <span>
                 <router-link to="/notifications" tag="a" class="btn p-0 btn-link my-auto text-body btn-sm">
                     <IconBell />
                 </router-link>
            </span>
            <span ref="dropdownToggle"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  id="dropdownMenuLikes"
                  @click="onShowNotifications()"
                  v-if="notificationsNumber() > 0" class="counter-info">
                    {{notificationsNumber()}}
                </span>
            <div aria-labelledby="dropdownMenuLikes"
                 :class="{'hidden': notificationsNumber() === 0}"
                 class="notifications-likes-dropdown dropdown-menu dropdown-menu-right pt-3 pb-0 dropdown-white w-auto">
                <vue-custom-scrollbar class="notifications-likes-scroll"
                                      :settings="customScrollbarSettings">
                    <ul class="list-unstyled mb-0">
                        <NotificationItem v-for="notifItem in notificationsList()"
                                          v-bind:notification="notifItem"
                                          v-bind:key="notifItem.id">
                        </NotificationItem>
                    </ul>
                </vue-custom-scrollbar>
                <div class="notifications-likes-dropdown-footer border-top">
                    <router-link to="/notifications" tag="a"
                                 class="notifications-link d-block text-center pt-1 pb-3">
                        Посмотреть все
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import IconBell from '../../icons/IconBell.vue';
    import NotificationItem from '../NotificationItem.vue';
    import vueCustomScrollbar from 'vue-custom-scrollbar';

    export default {
        name : 'NavBarNotifications',
        components : {
            IconBell,
            NotificationItem,
            vueCustomScrollbar,
        },

        data(){
            return {
                //notificationsNumber : 0,
                customScrollbarSettings: {
                    maxScrollbarLength: 60,
                    suppressScrollX: true, // rm scroll x
                    wheelPropagation: false
                }
            }
        },

        methods : {
            async onShowNotifications(){
                const idList = this.$root.$auth.nm.idsList;

                if (idList.length === 0) {
                    return;
                }
                await this.$root.$api.$notifications.markAsRead(idList);
            },

            updateNotifications(){
                this.$forceUpdate();
            },

            eventOnHideDropdown() {
                // TODO: Fix jquery
                $(this.$refs.dropdown).off('hidden.bs.dropdown').on('hidden.bs.dropdown', () => {
                    this.$root.$auth.nm.clear();
                    this.$root.$auth.nm.storeData();
                    this.updateNotifications();
                });
            },
            notificationsNumber(){
                return this.$root.$auth.nm.size;
            },

            notificationsList(){
                return this.$root.$auth.nm.asArray();
            }
        },
        created(){
            this.$root.$on(this.$root.$auth.nm.restoreEventName,  this.updateNotifications);
            this.$root.$on(this.$root.$auth.nm.loadEventName,  this.updateNotifications);
            this.$root.$on(this.$root.$auth.nm.updateEventName,  this.updateNotifications);
        },
        mounted() {
            this.eventOnHideDropdown();
        },
        updated() {
            this.eventOnHideDropdown();
        }
    }
</script>
