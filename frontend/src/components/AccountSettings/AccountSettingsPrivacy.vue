<template>
    <div id="accountSettingsPrivacy"
         class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <form class="plz-account-settings-form pb-2 px-3 mb-0">
            <div class="plz-account-settings-header row border-bottom">
                <div class="d-flex">
                    <h6 class="title-settings mb-0">Приватность</h6>
                </div>

            </div>

            <div class="plz-account-settings-body ">
                <div class="form-group row border-bottom">
                    <label for="pageType"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Тип страницы
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <i v-if="Number(form.pageType) !== 1" class="fas fa-lock mr-2"></i>
                            <i v-else class="fas fa-unlock mr-2"></i>
                            <div class="w-100 position-relative ml-n2">
                                <select id="pageType"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(form.pageType, 'pageType')"
                                        v-model="form.pageType">
                                    <option value="0" selected disabled>Выберите вариант</option>
                                    <option value="1">Открытая</option>
                                    <option value="2">Закрытая (Только друзья)</option>
                                    <option value="3">Закрытая</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="writeMessagesPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Кто может оставлять сообщения
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <div class="w-100 position-relative ml-n2">
                                <select id="writeMessagesPermissions"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(form.writeMessagesPermissions, 'writeMessagesPermissions')"
                                        v-model="form.writeMessagesPermissions">
                                    <option value="0" selected disabled>Выберите вариант</option>
                                    <option value="1">Все</option>
                                    <option value="2">Только друзья</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <!-- TODO: поправить, когда будет понятно что это за поле -->
                <div class="form-group row border-bottom d-none">
                    <label for="writeMessagesPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Вас могут
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <div class="w-100">
                                <!--                            <select id="writeMessagesPermissions"-->
                                <!--                                    class="form-control border-0 pl-2"-->
                                <!--                                    @change="accountStartSaveData(form.writeMessagesPermissions, 'pageType')"-->
                                <!--                                    v-model="form.writeMessagesPermissions">-->
                                <!--                                <option value="1">Все</option>-->
                                <!--                                <option value="2">Только друзья</option>-->
                                <!--                            </select>-->
                                <select id="writeMessagesPermissions"
                                        class="form-control border-0 pl-2">
                                    <option selected>Добавить в друзья</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <!-- TODO: поправить, когда будет понятно что это за поле -->
                <div class="form-group row border-bottom d-none">
                    <label for="writeMessagesPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Раздел сообщества
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <i class="fas fa-lock d-inline-block mr-2"></i>
                            <div class="w-100">
                                <!--                            <select id="writeMessagesPermissions"-->
                                <!--                                    class="form-control border-0 pl-2"-->
                                <!--                                    @change="accountStartSaveData(form.writeMessagesPermissions, 'pageType')"-->
                                <!--                                    v-model="form.writeMessagesPermissions">-->
                                <!--                                <option value="1">Все</option>-->
                                <!--                                <option value="2">Только друзья</option>-->
                                <!--                            </select>-->
                                <select id="writeMessagesPermissions"
                                        class="form-control border-0 pl-2">
                                    <option selected>Закрыт</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="postWallPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Кто может оставлять записи на стене
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <i class="fas fa-lock d-inline-block mr-2"></i>
                            <div class="w-100 position-relative ml-n2">
                                <select id="postWallPermissions"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(form.postWallPermissions, 'postWallPermissions')"
                                        v-model="form.postWallPermissions">
                                    <option value="0" selected disabled>Выберите вариант</option>
                                    <option value="1">Только друзья</option>
                                    <option value="2">Только я</option>
                                    <option value="3">Все пользователи</option>
                                    <option value="4">Выбранные друзья из списка</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="viewWallPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Кто может видеть записи на стене
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <i class="fas fa-lock d-inline-block mr-2"></i>
                            <div class="w-100 position-relative ml-n2">
                                <select id="viewWallPermissions"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(form.viewWallPermissions, 'viewWallPermissions')"
                                        v-model="form.viewWallPermissions">
                                    <option value="0" selected disabled>Выберите вариант</option>
                                    <option value="1">Все пользователи</option>
                                    <option value="2">Только друзья</option>
                                    <option value="3">Выбранные друзья из списка</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="viewFriendsPermissions"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Кто может видеть мой список друзей
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative pl-4 ">
                            <div class="w-100 position-relative ml-n2">
                                <select id="viewFriendsPermissions"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(form.viewFriendsPermissions, 'viewFriendsPermissions')"
                                        v-model="form.viewFriendsPermissions">
                                    <option value="0" selected disabled>Выберите вариант</option>
                                    <option value="1">Все пользователи</option>
                                    <option value="2">Только друзья</option>
                                    <option value="3">Только друзья и друзья друзей</option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'AccountSettingsPrivacy',
        computed: {
            userData: function () {
                return this.$root.$auth.user;
            },
        },
        data() {
            return {
                form: {
                    pageType: this.$root.$auth.user.privacySettings.pageType,
                    writeMessagesPermissions: this.$root.$auth.user.privacySettings.writeMessagesPermissions,
                    postWallPermissions: this.$root.$auth.user.privacySettings.postWallPermissions,
                    viewWallPermissions: this.$root.$auth.user.privacySettings.viewWallPermissions,
                    viewFriendsPermissions: this.$root.$auth.user.privacySettings.viewFriendsPermissions,
                },
            }
        },
        methods: {
            async accountStartSaveData(newValue, fieldName) {
                let response = null;

                try {
                    response = await this.$root.$api.updateUserPrivacy({[fieldName]: newValue});
                } catch (e) {
                    console.log(e.detailMessage);
                }

                if (response) {
                    this.$root.$auth.user.updateAuthUser(response);
                    this.$root.$auth.storeUserData();
                }
            },
        },
    }
</script>
