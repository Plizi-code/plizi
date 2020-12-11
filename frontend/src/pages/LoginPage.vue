<template>
    <div class="row">
        <div class="col-12 col-lg-5 d-flex px-0 mb-3 mb-lg-0">
            <LoginForm ref="loginForm"></LoginForm>
        </div>

        <div class="col-12 col-lg-7 pr-0 pl-0 pl-lg-3 pb-0" >
            <LastEntries @logInWithEntryItem="logInWithEntryItem"></LastEntries>
            <PliziMobile v-if="!checkIsTarga()"></PliziMobile>
        </div>
    </div>
</template>

<script>
import LoginForm from '../components/Login/LoginForm.vue';
import LastEntries from '../components/Login/LastEntries.vue';
import PliziMobile from '../components/Login/PliziMobile.vue';

export default {
name: 'LoginPage',
components: {LoginForm, LastEntries, PliziMobile},
data() {
    return {}
},

methods: {
    logInWithEntryItem(entry) {
        if (!entry)
            return;

        if (entry.isUser) {
            this.$refs.loginForm.model.email = entry.email;
            this.$refs.loginForm.model.password = entry.password || `secret`;
            this.$refs.loginForm.startLogin();
            return;
        }

        this.$refs.loginForm.openRegistrationModal();
    },

    // TODO: удалить позже
    checkIsTarga(){
        return (typeof isTarga !== 'undefined'  &&  !!isTarga);
    }
},

created(){
    this.$root.$isAuth = false;
},

mounted() {
    if (this.$route.query.auto) {
        this.$refs.loginForm.model.email = this.$route.query.auto;
        this.$refs.loginForm.model.password = `secret`;
        this.$refs.loginForm.startLogin();
    }
    this.$root.$emit('afterSuccessLogout', {redirect: false});
},
}
</script>
