<template>
        <div id="plzNotification" >
            <transition-group tag="div" name="slide-fade" :duration="400">
                <div
                    class="plz-notification d-flex  align-items-start p-4 mb-2"
                    v-for="notification in notifications"
                    :key="notification.id"
                >
                    <div class="plz-notification-pic mr-3">
                        <img v-if="notification.isHuman" :src="notification.userPic" alt="image" />
                        <img v-else :src="notification.primaryImage" alt="image" />
                    </div>
                    <div class="plz-notification-body mr-3">
                        <h6 v-if="notification.isHuman" class="plz-notification-name mb-2">{{ notification.firstName }} {{ notification.lastName }}</h6>
                        <h6 v-else class="plz-notification-name mb-2">{{ notification.name }} </h6>
                        <div class="plz-notification-text" v-html="notification.body"></div>
                    </div>
                    <button class="btn btn-close pt-0 pr-0 ml-auto" @click="removeNotification(notification)">
                        <i class="icon icon-close-notification"></i>
                    </button>
                </div>
            </transition-group>
        </div>
</template>

<script>
export default {
name: 'AppNotifications',
props: {
    notifications: {
        type: Array,
        default: () => [],
    }
},
methods: {
    removeNotification(notification) {
        this.$emit('removeNotification', notification);
    }
}
}
</script>
