<template>
    <div id="loginForm" class="plz-login-form bg-white-br20 mr-0 pt-3">
        <div class="card-body">
            <form id="loginFormForm" novalidate="novalidate">
                <div class="form-group"
                     :class="{ 'has-error': $v.model.email.$error, 'has-success': !$v.model.email.$invalid, 'has-error': isServerError }">
                    <label for="userEmail" class="d-none">Ваш E-mail</label>
                    <i class="icon icon-letter"></i>
                    <input v-model="model.email" ref="email"
                           :class="{ '--is-invalid': $v.model.email.$error, '--is-valid': !$v.model.email.$invalid }"
                           @blur="$v.model.email.$touch()"
                           @keydown="loginKeyDownCheck($event)"
                           @input="onInput('email')"
                           type="text" class="lr-input lr-input-email form-control" id="userEmail"
                           placeholder="Ваш E-mail"/>

                    <div v-show="$v.model.email.$error" class="invalid-feedback">
                        <p v-if="!$v.model.email.required" class="text-danger">Укажите свой е-мейл</p>
                        <p v-if="!$v.model.email.email" class="text-danger">Укажите корректный е-мейл</p>
                    </div>
                </div>

                <div class="form-group"
                     :class="{ 'has-error': $v.model.password.$error, 'has-success': !$v.model.password.$invalid }">
                    <label for="password" class="d-none">Пароль</label>
                    <i class="icon icon-user"></i>
                    <input v-model="model.password" ref="password"
                           :class="{ '--is-invalid': $v.model.password.$error, '--is-valid': !$v.model.password.$invalid }"
                           @blur="$v.model.password.$touch()" @keydown="loginKeyDownCheck($event)"
                           type="password" class="lr-input lr-input-password form-control" id="password"
                           placeholder="Пароль"/>

                    <div v-show="$v.model.password.$error" class="invalid-feedback">
                        <p v-if="!$v.model.password.required" class="text-danger">Укажите свой пароль</p>
                        <p v-if="!$v.model.password.minLength" class="text-danger">Пароль не может быть короче <b>четырёх</b> символов</p>
                        <p v-if="!$v.model.password.maxLength" class="text-danger">Слишком длинный пароль</p>
                    </div>
                </div>

                <div v-if="isServerError" class="form-group has-error">
                    <p class="text-danger text-center">Неверный пароль или имя пользователя</p>
                </div>

                <div class="form-group">
                    <button id="btnLogin" type="button"
                            @click="initLogin()" :disabled="$v.$invalid"
                            class="btn-login btn plz-btn plz-btn-primary">Войти
                    </button>
                    <button id="btnRegistration" type="button"
                            class="btn-registration btn plz-btn plz-btn-outline" @click="openRegistrationModal()">
                        Регистрация
                    </button>
                    <button id="btnRecoverPass" type="button"
                            class="btn-recover-pas btn plz-btn btn-link mt-3 "  @click="openRecoverPassModal()">
                        Восстановить пароль
                    </button>
                </div>
                <LoginSocialLinks></LoginSocialLinks>
            </form>
        </div>

        <RegistrationModal v-if="isRegistrationModalShow"
                           v-bind:reg-modal-visible="isRegistrationModalShow"
                           @successRegistration="successRegistration"></RegistrationModal>

        <RecoverPassModal  v-if="isRecoverPassModalShow"
                           v-bind:reg-modal-visible="isRecoverPassModalShow">
        </RecoverPassModal>
    </div>
</template>

<script>
    import RegistrationModal from '../RegistrationModal.vue';
    import RecoverPassModal from '../RecoverPassModal.vue';
    import {email, maxLength, minLength, required} from 'vuelidate/lib/validators';
    import client_ids from '../../libs/social_networks_client_ids';
    import LoginSocialLinks from "./LoginSocialLinks";

    export default {
        name: 'LoginForm',
        components: {
            RegistrationModal,
            RecoverPassModal,
            LoginSocialLinks,
        },

        data() {
            return {
                model: {
                    email: ``,
                    password: ``
                },
                isRegistrationModalShow: false,
                isRecoverPassModalShow: false,
                isServerError: false,
                serverErrorText: '',
            }
        },

        validations() {
            return {
                model: {
                    email: {
                        required,
                        email
                    },
                    password: {
                        required,
                        minLength: minLength(4),
                        maxLength: maxLength(50)
                    }
                }
            };
        },

        mounted() {
            setTimeout(() => {
                this.$refs.email.focus();
            }, 100);

            this.$root.$on('hideRegistrationModal', (evData) => {
                this.isRegistrationModalShow = false;
            });
            this.$root.$on('hideRecoverPassModal', (evData) => {
                this.isRecoverPassModalShow = false;
            });

            if (this.$route.params) {
                this.loginWithCredentialsFromRoute(this.$route.params);
            }
        },

        methods: {
            async initLogin(){
                // обёртка, чтобы ошибка при 400-х не выпадал "наружу"
                await this.startLogin().catch(()=>{ });
            },
            async startLogin() {
                this.$v.$touch();
                this.isServerError = false;

                let response = null;

                try {
                    response = await this.$root.$api.login(this.model.email.trim(), this.model.password.trim());
                }
                catch (e){
                    if (e.status >= 400) {
                        this.isServerError = true;
                        this.serverErrorText = e.data.message;
                        this.$refs.password.focus();
                    }
                }

                if (response  &&  response.status === 200 && response.data && response.data.token && response.data.token !== '') {
                    this.$root.$emit('afterSuccessLogin', {
                        token: response.data.token,
                        chatChannel: response.data.channel,
                        redirect: true
                    });
                }
            },

            loginKeyDownCheck( ev ){
                if ( 13 === ev.keyCode ){
                    return this.startLogin();
                }
            },
            openRegistrationModal() {
                this.isRegistrationModalShow = true;
            },
            openRecoverPassModal() {
                this.isRecoverPassModalShow = true;
            },
            successRegistration(user) {
                this.model.email = user.email;
                this.$refs.password.focus();
            },
            successRecoverPass(user) {
                this.model.email = user.email;
                this.$refs.password.focus();
            },
            loginWithCredentialsFromRoute(params) {
                if (params.email && params.password) {
                    this.model.email = params.email;
                    this.model.password = params.password;

                    this.initLogin();
                }
            },
            onInput(fieldName) {
                if (fieldName === 'email')
                    this.model[fieldName] = this.model[fieldName].trim();
            },
        },
    }
</script>
