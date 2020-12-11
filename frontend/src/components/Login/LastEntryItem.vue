<template>
    <div class="plz-latest-entries-item mb-1">
        <img v-bind:src="userPic" class="plz-latest-entries-user-pic mb-3" :alt="fullName" />
        <div class="d-inline-block text-center mx-auto px-4 my-0">
            <a href="/profile">
                {{ fullName }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'LastEntryItem',
        props: {
            entryItem: Object
        },
        data() {
            return {}
        },
        computed: {
            userPic() {
                return this.entryItem.src || this.entryItem.userPic || '/images/noavatar-256.png';
            },
            fullName() {
                return this.entryItem.fullName || `${this.entryItem.firstName} ${this.entryItem.lastName}`;
            },
        },
    }
</script>
