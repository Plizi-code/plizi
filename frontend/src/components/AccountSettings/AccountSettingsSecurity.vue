<template>
    <div id="accountSettingsSecurity"
         class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <form class="plz-account-settings-form pb-0 px-3 mb-0">
            <div class="plz-account-settings-header row border-bottom">
                <div class="d-flex">
                    <h6>Безопасность</h6>
                </div>
            </div>

            <div class="plz-account-settings-body">
                <div class="form-group row border-bottom d-none">
                    <label for="twoFactorAuthEnabled"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Двухэтапная аутентификация
                    </label>
                    <div
                        class="plz-account-settings-body-field  col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4">
                            <i v-if="Number(form.twoFactorAuthEnabled) === 1" class="fas fa-check mr-2"></i>
                            <div class="w-100 position-relative ml-n2">
                                <select id="twoFactorAuthEnabled"
                                        class="form-control border-0 pl-0"
                                        @change="accountStartSaveData(form.twoFactorAuthEnabled, 'twoFactorAuthEnabled')"
                                        v-model="form.twoFactorAuthEnabled">
                                    <option value="0">Выключена</option>
                                    <option value="1">Включена</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom d-none">
                    <label for="smsConfirm"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Подтверждение через SMS
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4">
                            <i v-if="Number(form.smsConfirm) === 1" class="fas fa-check mr-2"></i>
                            <div class="w-100  position-relative ml-n2">
                                <select id="smsConfirm"
                                        class="form-control border-0 pl-0"
                                        @change="accountStartSaveData(form.smsConfirm, 'smsConfirm')"
                                        v-model="form.smsConfirm">
                                    <option value="0">Выключено</option>
                                    <option value="1">Включено</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="email"
                           class="plz-account-settings-body-label col-6 col-lg-4">Email</label>
                    <div class="plz-account-settings-body-action justify-content-end justify-content-sm-start col-6 text-right text-sm-right pl-1">
                        <button type="button"
                                id="email"
                                class="btn btn-link"
                                @click="openChangeEmailModal">
                            Изменить
                        </button>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="password"
                           class="plz-account-settings-body-label col-6 col-lg-4 ">Пароль</label>

                    <div class="plz-account-settings-body-action justify-content-end justify-content-sm-start col-6 text-right text-sm-right pl-1">
                        <button type="button"
                                id="password"
                                class="btn btn-link"
                                @click="openChangePasswordModal">
                            Изменить
                        </button>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </form>

        <ChangePasswordModal v-if="changePasswordModal.isVisible"
                             @hideModal="hideChangePasswordModal"/>

        <ChangeEmailModal v-if="changeEmailModal.isVisible"
                          @hideModal="hideChangeEmailModal"/>
    </div>
</template>

<script>
    import ChangePasswordModal from "./ChangePasswordModal.vue";
    import ChangeEmailModal from "./ChangeEmailModal.vue";

    export default {
        name: 'AccountSettingsSecurity',
        components: {
            ChangePasswordModal,
            ChangeEmailModal,
        },
        data() {
            return {
                form: {
                    twoFactorAuthEnabled: this.$root.$auth.user.privacySettings.twoFactorAuthEnabled,
                    smsConfirm: this.$root.$auth.user.privacySettings.smsConfirm,
                },
                changePasswordModal: {
                    isVisible: false,
                },
                changeEmailModal: {
                    isVisible: false,
                },
            }
        },
        methods: {
            openChangePasswordModal() {
                this.changePasswordModal.isVisible = true;
            },
            hideChangePasswordModal() {
                this.changePasswordModal.isVisible = false;
            },
            openChangeEmailModal() {
                this.changeEmailModal.isVisible = true;
            },
            hideChangeEmailModal() {
                this.changeEmailModal.isVisible = false;
            },

            async accountStartSaveData(newValue, fieldName) {
                let response = null;

                try {
                    response = await this.$root.$api.updateUserPrivacy({[fieldName]: newValue});
                } catch (e) {
                    console.log(e.detailMessage);
                }

                if (response) {
                    this.$root.$auth.user.updateAuthUser({profile: response});
                    this.$root.$auth.storeUserData();
                }
            },

        },
    }
</script>
