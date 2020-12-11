<template>
    <div class="modal" id="changeEmailModal" tabindex="-1" role="dialog" aria-labelledby="changeEmailModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hideModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <div class="modal-header border-0  justify-content-center pb-0">
                    <h5 class="modal-title " id="changeEmailModalTitle">Обновление email</h5>
                </div>
                <div class="modal-body p-4">
                    <form id="refreshPassword" novalidate="novalidate">
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label class="d-none" for="oldEmail">Старый email</label>
                            <input type="email"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': oldEmailError}"
                                   id="oldEmail"
                                   placeholder="Старый email"
                                   @input="oldEmailError ? formErrors.oldEmail = null : null"
                                   v-model="email.oldEmail">
                            <div v-if="oldEmailError" class="invalid-feedback">
                                {{ oldEmailError }}
                            </div>
                        </div>
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label class="d-none" for="newEmail">Новый email</label>
                            <input type="email"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': newEmailError}"
                                   id="newEmail"
                                   placeholder="Новый email"
                                   @input="newEmailError ? formErrors.newEmail = null : null"
                                   v-model="email.newEmail">
                            <div v-if="newEmailError" class="invalid-feedback">
                                {{ newEmailError }}
                            </div>
                        </div>
                        <div class="form-group">
                            <i class="icon icon-key"></i>
                            <label class="d-none" for="newEmailConfirmation">Подтвердите новый email</label>
                            <input type="email"
                                   class="lr-input form-control"
                                   :class="{'is-invalid': newEmailConfirmationError}"
                                   placeholder="Подтвердите новый email"
                                   id="newEmailConfirmation"
                                   @input="newEmailConfirmationError ? formErrors.newEmailConfirmation = null : null"
                                   v-model="email.newEmailConfirmation">
                            <div v-if="newEmailConfirmationError" class="invalid-feedback">
                                {{ newEmailConfirmationError }}
                            </div>
                        </div>
                        <div v-if="isFormSuccess" class="text-success mb-3">
                            {{ formSuccess }}
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                    class="btn plz-btn btn-primary btn-submit"
                                    @click.prevent="changeEmail">
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
        name: "ChangeEmailModal",
        computed: {
            oldEmailError() {
                return this.formErrors && this.formErrors.oldEmail ? this.formErrors.oldEmail[0] : null;
            },
            newEmailError() {
                return this.formErrors && this.formErrors.newEmail ? this.formErrors.newEmail[0] : null;
            },
            newEmailConfirmationError() {
                return this.formErrors && this.formErrors.newEmailConfirmation ?
                    this.formErrors.newEmailConfirmation[0] : null;
            },
            isFormSuccess() {
                return !!this.formSuccess;
            },
        },
        data() {
            return {
                email: {
                    oldEmail: null,
                    newEmail: null,
                    newEmailConfirmation: null,
                },
                formErrors: null,
                formSuccess: null,
            }
        },
        methods: {
            hideModal() {
                this.$emit('hideModal');
            },

            async changeEmail() {
                this.formErrors = null;
                this.formSuccess = null;

                let response = null;

                try {
                    response = await this.$root.$api.$users.changeEmail({
                        oldEmail: this.email.oldEmail,
                        newEmail: this.email.newEmail,
                        newEmailConfirmation: this.email.newEmailConfirmation,
                    });
                } catch (e) {
                    this.formErrors = e.data.errors;
                    console.log(e.detailMessage);
                }

                if (response) {
                    this.formSuccess = response.message;
                }
            },
        },
    }
</script>

<style scoped>

</style>
