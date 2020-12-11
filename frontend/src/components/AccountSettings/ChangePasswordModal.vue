<template>
    <div class="modal" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hideModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <div class="modal-header border-0  justify-content-center pb-0">
                    <h5 class="modal-title " id="changePasswordModalTitle">Обновление пароля</h5>
                </div>
                <div class="modal-body p-4">
                    <form id="refreshPassword" novalidate="novalidate">
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label  class="d-none" for="old_password">Старый пароль</label>
                            <input type="password"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': oldPasswordError}"
                                   id="old_password"
                                   placeholder="Старый пароль"
                                   @input="oldPasswordError ? formErrors.oldPassword = null : null"
                                   v-model="passwords.oldPassword">
                            <div v-if="oldPasswordError" class="invalid-feedback">
                                {{ oldPasswordError }}
                            </div>
                        </div>
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label  class="d-none" for="new_password">Новый пароль</label>
                            <input type="password"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': newPasswordError}"
                                   id="new_password"
                                   placeholder="Новый пароль"
                                   @input="newPasswordError ? formErrors.newPassword = null : null"
                                   v-model="passwords.newPassword">
                            <div v-if="newPasswordError" class="invalid-feedback">
                                {{ newPasswordError }}
                            </div>
                        </div>
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label class="d-none" for="new_password_confirmation">Подтвердите новый пароль</label>
                            <input type="password"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': newPasswordConfirmationError}"
                                   placeholder="Подтвердите новый пароль"
                                   id="new_password_confirmation"
                                   @input="newPasswordConfirmationError ? formErrors.newPasswordConfirmation = null : null"
                                   v-model="passwords.newPasswordConfirmation">
                            <div v-if="newPasswordConfirmationError" class="invalid-feedback">
                                {{ newPasswordConfirmationError }}
                            </div>
                        </div>
                        <div v-if="isFormSuccess" class="text-success mb-3">
                            {{ formSuccess }}
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                    class="btn plz-btn btn-primary btn-submit"
                                    @click.prevent="changePassword">
                                Обновить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChangePasswordModal",
        computed: {
            oldPasswordError() {
                return this.formErrors && this.formErrors.oldPassword ? this.formErrors.oldPassword[0] : null;
            },
            newPasswordError() {
                return this.formErrors && this.formErrors.newPassword ? this.formErrors.newPassword[0] : null;
            },
            newPasswordConfirmationError() {
                return this.formErrors && this.formErrors.newPasswordConfirmation ?
                  this.formErrors.newPasswordConfirmation[0] : null;
            },
            isFormSuccess() {
                return !!this.formSuccess;
            },
        },
        data() {
            return {
                passwords: {
                    oldPassword: null,
                    newPassword: null,
                    newPasswordConfirmation: null,
                },
                formErrors: null,
                formSuccess: null,
            }
        },
        methods: {
            hideModal() {
                this.$emit('hideModal');
            },

            async changePassword() {
                this.formErrors = null;
                this.formSuccess = null;

                let response = null;

                try {
                    response = await this.$root.$api.changePassword({
                        oldPassword: this.passwords.oldPassword,
                        newPassword: this.passwords.newPassword,
                        newPasswordConfirmation: this.passwords.newPasswordConfirmation,
                    });
                } catch (e) {
                    this.formErrors =  e.data.errors;
                    console.log(e.detailMessage);
                }

                if (response) {
                    this.formSuccess = response.message;
                }
            },
        },
    }
</script>

