<template>
    <div class="modal show" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true"
         style="display: block; background-color: rgba(0, 0, 0, .7);" @click.stop="onHide">
        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <div class="modal-body">
                    <div id="" class="plz-last-entry-form-wrapper">
                        <div class="text-center"><h5>Быстрый вход</h5></div>
                        <div class="plz-last-entry-userPic text-center">
                            <img :src="userPic"
                                 class="rounded-circle"
                                 :alt="fullName">
                        </div>
                        <div class="plz-last-entry-fullName text-center">
                            <p class="text-bold">
                                {{ `${entry.firstName} ${entry.lastName}` }}
                            </p>
                        </div>
                        <div class="plz-last-entry-email text-center">
                            <p>{{ entry.email }}</p>
                        </div>

                        <form id="lastEntryForm" novalidate="novalidate" @submit.prevent="login">
                            <div class="form-group"
                                 :class="{ 'has-error': !!isPasswordErrors }">
                                <label for="password" class="d-none">Ваш пароль</label>
                                <input id="password"
                                       class="form-control"
                                       type="password"
                                       ref="password"
                                       placeholder="Ваш пароль"
                                       v-model="form.password"
                                       :class="{ 'is-invalid': !!isPasswordErrors }"
                                       @input="onInput('password')"
                                       @blur="$v.form.password.$touch()">

                                <div class="invalid-feedback">
                                    <p v-if="this.errors && this.errors.password">
                                        {{ this.errors.password[0] }}
                                    </p>
                                    <p v-if="!this.$v.form.password.required">
                                        Поле Ваш пароль обязательно для заполнения.
                                    </p>
                                    <p v-if="!this.$v.form.password.minLength">
                                        Поле Ваш пароль не может быть менее 6 символов.
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <button id="btnRegistration"
                                        type="submit"
                                        :disabled="$v.$invalid"
                                        class="btn plz-btn plz-btn-primary text-center">
                                        Войти
                                </button>
                                <p v-if="!!serverError" class="text-danger mt-3 mb-0 text-center">
                                    {{ serverError }}
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators';
    import PliziLastEntry from "../../classes/PliziLastEntry.js";

    export default {
        name: "LastEntryModal",
        props: {
            entry: PliziLastEntry,
        },
        data() {
            return {
                form: {
                    password: null,
                },
                errors: null,
                serverError: null,
            }
        },
        computed: {
            isPasswordErrors() {
                return this.$v.form.password.$error || (this.errors && this.errors.length);
            },
            userPic() {
                return this.entry.src || this.entry.userPic || '/images/noavatar-256.png';
            },
            fullName() {
                return this.entry.fullName || `${this.entry.firstName} ${this.entry.lastName}`;
            },
        },
        validations() {
            return {
                form: {
                    password: {
                        required,
                        minLength: minLength(6),
                    },
                },
            };
        },
        methods: {
            onHide() {
                this.$emit('onHide');
            },
            onInput(fieldName) {
                if (this.errors && this.errors[fieldName]) {
                    this.errors[fieldName] = null;
                }
            },

            async login() {
                this.$v.$touch();
                this.errors = [];

                let response = null;

                try {
                    response = await this.$root.$api.login(this.entry.email.trim(), this.form.password.trim());
                }
                catch (e) {
                    console.warn(e.detailMessage);

                    if (e.status >= 400) {
                        this.serverError = e.data.message;
                        this.$refs.password.focus();
                    }

                    if (e.status === 422) {
                        this.errors = e.data.errors;
                    }
                }

                if (response) {
                    this.$root.$emit('afterSuccessLogin', {
                        token: response.data.token,
                        chatChannel: response.data.channel,
                        redirect: true
                    });
                }
            }
        },
        mounted() {
            setTimeout(() => {
                this.$refs.password.focus();
            }, 100);
        },
    }
</script>

<style lang="scss">
    .plz-last-entry-form-wrapper {
        .plz-last-entry-userPic {
            img {
                width: 100px;
                height: 100px;
            }
        }
    }
</style>
