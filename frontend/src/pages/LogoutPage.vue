<template>
    <div class="alert alert-info">Выходим...</div>
</template>

<script>
export default {
name: 'LogoutPage',

beforeMount() {
    this.$root.$isAuth = false;

    window.localStorage.removeItem('pliziJWToken');
    window.localStorage.removeItem('pliziUser');
    window.localStorage.removeItem('pliziChatChannel');

    window.localStorage.removeItem('pliziLastSearch');

    window.localStorage.removeItem('pliziFriends');
    window.localStorage.removeItem('pliziFavorites');
    window.localStorage.removeItem('pliziDialogs');
    window.localStorage.removeItem('pliziInvitations');
    window.localStorage.removeItem('pliziNotifications');

    this.$router.push({path: '/login'});
    //window.location = window.location.origin + '/login'; // @TGA: for app restart
},


}
</script>
