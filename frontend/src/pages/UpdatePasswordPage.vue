<template>
    <div class="row">
        <div class="w-100 bg-white-br20 p-5">
                <h4 v-if="!successMessage" class="update-password-title text-center text-md-left mb-4">Восстановление пароля</h4>

                <form v-if="!successMessage"
                      class="update-password-form w-100 mb-0"
                      method="POST"
                      @submit.prevent="send">
                    <div class="d-flex flex-wrap justify-content-center update-password-form-box row align-items-center mb-4">
                        <input type="hidden" name="token" v-model="form.token">

                        <div class="form-group col-12 col-md-4 position-relative">
                            <label for="email" class="d-none col-form-label text-md-right">Ваш E-mail</label>

                            <div class="position-relative">
                                <i class="icon icon-letter"></i>
                                <input id="email"
                                       type="email"
                                       class="form-control"
                                       name="email"
                                       v-model="form.email"
                                       :class="{'is-invalid': errors && errors['email']}"
                                       @input="errors && errors['email'] ? errors['email'] = null : null"
                                       autocomplete="email"
                                       placeholder="Ваш E-mail"
                                       required
                                       autofocus>
                                <div v-if="errors && errors['email']" class="invalid-feedback">
                                    <p class="text-danger">{{ errors['email'][0] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4 position-relative">
                            <label for="password" class="d-none col-form-label text-md-right">Ваш новый пароль</label>

                            <div class="position-relative">
                                <i class="icon icon-key"></i>
                                <input id="password"
                                       type="password"
                                       class="form-control"
                                       name="password"
                                       v-model="form.password"
                                       :class="{'is-invalid': errors && errors['password']}"
                                       @input="errors && errors['password'] ? errors['password'] = null : null"
                                       autocomplete="new-password"
                                       placeholder="Ваш новый пароль"
                                       required>
                                <div v-if="errors && errors['password']" class="invalid-feedback">
                                    <p class="text-danger">{{ errors['password'][0] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4 position-relative">
                            <label for="password-confirm" class="d-none col-form-label text-md-right">
                                Подтвердите новый пароль
                            </label>

                            <div class="position-relative">
                                <i class="icon icon-key"></i>
                                <input id="password-confirm"
                                       type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       v-model="form.password_confirmation"
                                       autocomplete="new-password"
                                       placeholder="Подтвердите новый пароль"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-12 col-md-4 p-2 mx-auto">
                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-100 text-uppercase">
                                Обновить пароль
                            </button>
                        </div>
                    </div>

                    <div v-if="successMessage" class="text-center mt-3">
                        <p class="text-success">{{ successMessage }}</p>
                    </div>
                </form>
                <div v-else class="update-password-success text-center">
                    <IconChecked class="update-password-success-icon-checked"/>
                    <p class="update-password-success-message">
                        Ваш пароль изменен.
                    </p>
                    <div class="d-flex justify-content-center">
                        <router-link :to="{name: 'LoginPage', params: {email: form.email, password: form.password}}"
                                     class="btn plz-btn-primary update-password-success-btn">
                            Войти
                        </router-link>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    import IconMessage from "../icons/IconMessage";
    import IconChecked from "../icons/IconChecked";

    export default {
        name: "UpdatePasswordPage",
        components: {
            IconMessage,
            IconChecked,
        },
        data() {
            return {
                form: {
                    token: this.getParam('token'),
                    email: this.getParam('email'),
                    password: null,
                    password_confirmation: null,
                },
                errors: null,
                successMessage: null,
            }
        },
        methods: {
            getParam(param) {
                let params = new URLSearchParams(location.search);

                return params.get(param);
            },
            async send() {
                let response;
                this.errors = null;

                try {
                    response = await this.$root.$api.resetPassword(this.form);
                } catch (e) {
                    if (e.status === 422) {
                        this.errors = e.data.errors;
                    }
                }

                if (response) {
                    this.successMessage = response.message;
                }
            },
        },
    }
</script>


