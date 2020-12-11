<template>
    <div id="accountSettingsMain"
         class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <form class="plz-account-settings-form pb-0 px-3 mb-0">
            <div class="plz-account-settings-header row border-bottom">
                <div class="d-flex">
                    <h6 class="title-settings mb-0">Основные</h6>
                </div>
            </div>

            <div class="plz-account-settings-body ">

                <div class="form-group row border-bottom">
                    <label for="firstName"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Имя
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <input type="text"
                               id="firstName"
                               class="w-50 w-sm-75"
                               v-model="model.firstName"
                               :class="[isEdit.firstName ? 'form-control' : 'form-control-plaintext', { 'is-invalid': !!firstNameError, 'is-valid': isSuccessFirstName }]"
                               @input="inputFieldEdit($event, 'firstName')"
                               @keyup.enter="accountStartSaveData($event.target.value, `firstName`)"
                               @blur="finishFieldEdit(`firstName`)"
                               :readonly="!isEdit.firstName"
                               ref="firstName">

                        <div class="invalid-feedback">
                            <p class="text-danger">{{ firstNameError }}</p>
                        </div>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.firstName}"
                                @click="[isEdit.firstName ? finishFieldEdit('firstName') : startFieldEdit('firstName')]">
                            {{ isEdit.firstName ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="lastName"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4">Фамилия</label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <input id="lastName"
                               type="text"
                               class="w-50 w-sm-75"
                               v-model="model.lastName"
                               :class="[isEdit.lastName ? 'form-control' : 'form-control-plaintext', { 'is-invalid': !!lastNameError, 'is-valid': isSuccessLastName }]"
                               @input="inputFieldEdit($event, 'lastName')"
                               @keyup.enter="accountStartSaveData($event.target.value, `lastName`)"
                               @blur="finishFieldEdit(`lastName`)"
                               :readonly="!isEdit.lastName"
                               ref="lastName">

                        <div class="invalid-feedback">
                            <p class="text-danger">{{ lastNameError }}</p>
                        </div>
                    </div>
                    <div
                        class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.firstName}"
                                @click="[isEdit.lastName ? finishFieldEdit('lastName') : startFieldEdit('lastName')]">
                            {{ isEdit.lastName ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="userSex"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">Пол</label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5  col-lg-6 ">
                        <div class="w-100 w-sm-75  position-relative ml-n2">
                            <select id="userSex" class="form-control border-0 pl-2"
                                    @change="accountStartSaveData(model.sex, 'sex')"
                                    v-model="model.sex">
                                <option value="n">Не указано</option>
                                <option value="m">Мужской</option>
                                <option value="f">Женский</option>
                            </select>
                            <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="relationship"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4">
                        Семейной положение
                    </label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-md-6 col-lg-6 col-xl-6">
                        <div class="w-100 w-sm-75 position-relative ml-n2">
                            <select id="relationship" class="form-control border-0 pl-2"
                                    @change="accountStartSaveData(model.relationshipId, 'relationshipId')"
                                    v-model="model.relationshipId">
                                <option value="null" selected disabled>Выберите вариант</option>
                                <option value="1">В браке</option>
                                <option value="2">Не в браке</option>
                                <option value="3">В активном поиске</option>
                                <option value="4">Встречаюсь</option>
                                <option value="5">В отношениях</option>
                            </select>
                            <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                        </div>
                    </div>
                    <div class="col-3 d-sm-none d-md-none"></div>
                </div>

                <div v-if="canEditRelationUser" class="form-group row border-bottom">
                    <label for="relationshipUsers"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4">
                        С кем
                    </label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-md-6 col-lg-6 col-xl-6">
                        <div class="w-100 w-sm-75 position-relative ">
                            <multiselect id="relationshipUsers"
                                         class="w-100 w-sm-75 border-0 form-control p-0 position-relative relationshipUsers"
                                         v-model="model.relationshipUser"
                                         :options="friends"
                                         :showLabels="false"
                                         @select="accountStartSaveData"
                                         track-by="id"
                                         label="id"
                                         placeholder="Выберите друга"
                                         :custom-label="relationshipUserLabel"
                                         @search-change="getFriends">
                                <template slot="noOptions">Результатов нет.</template>
                                <template slot="noResult">У Вас нет такого друга.</template>
                            </multiselect>
                            <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                        </div>
                    </div>
                    <div class="col-3 d-sm-none d-md-none"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="birthday"
                           class="plz-account-settings-body-label col-6 col-sm-4 ">
                        Дата рождения
                    </label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6  ">
                        <div v-if="!isEdit.birthday" class="form-control-plaintext border-bottom-0">
                            <template v-if="model.birthday">
                                {{ model.birthday | toDMY }}
                            </template>
                            <template v-else>
                                Не указано
                            </template>
                        </div>
                        <input v-if="isEdit.birthday"
                               id="birthday"
                               type="date"
                               class="form-control w-50 w-sm-75"
                               :class="{ 'is-invalid': !!birthdayError, 'is-valid': isSuccessBirthday }"
                               :value="model.birthday | toYMD"
                               @input="inputFieldEdit($event, 'birthday')"
                               @keyup.enter="accountStartSaveData($event.target.value, `birthday`)"
                               @blur="finishFieldEdit(`birthday`)"
                               ref="birthday"/>

                        <div class="invalid-feedback">
                            <p class="text-danger">{{ birthdayError }}</p>
                        </div>
                    </div>
                    <div
                        class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 col-xl-auto d-lg-flex ">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.birthday}"
                                @click="[isEdit.birthday ? finishFieldEdit('birthday') : startFieldEdit('birthday')]">
                            {{ isEdit.birthday ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom d-flex ">
                    <label for="location"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4">
                        Месторасположение
                    </label>
                    <div
                        class="plz-account-settings-body-field order-1 order-sm-0 col-12 pl-4 col-6 col-lg-8  d-flex align-items-center">
                        <i class="fas fa-map-marker-alt"></i>
                        <multiselect id="location"
                                     class="w-100 w-sm-75 ml-1 border-0 form-control p-0 position-relative"
                                     v-model="model.location"
                                     :options="geoLocations"
                                     :showLabels="false"
                                     @select="accountStartSaveData"
                                     track-by="title"
                                     label="title"
                                     placeholder="Выберите местоположение"
                                     :custom-label="locationLabel"
                                     @search-change="getLocations">
                            <template slot="noOptions">Результатов нет.</template>
                            <template slot="noResult">Такого города не существует.</template>
                        </multiselect>
                    </div>
                </div>

                <!--            location для мелких
                <div class="form-group row mb-0 border-bottom d-sm-block d-md-block d-lg-none d-xl-none">
                    <label class="col-12 col-form-label text-secondary">Контакты</label>
                </div>

                <div class="form-group row mb-0 border-bottom d-lg-none d-xl-none">
                    <label for="country" class="col-4 col-sm-6 col-md-6 col-form-label text-secondary">Страна</label>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <input type="text" readonly class="form-control-plaintext d-inline-block w-50" id="country"
                               ref="country"/>
                    </div>
                </div>

                <div class="form-group row mb-0 --border-bottom d-lg-none d-xl-none">
                    <label for="city" class="col-4 col-sm-6 col-md-6 col-form-label text-secondary">Город</label>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <input type="text" readonly
                               class="form-control-plaintext d-inline-block w-50" id="city" ref="city"/>
                    </div>
                </div>
                -->

            </div>
        </form>
    </div>
</template>

<script>
    import {required, minLength, maxLength} from 'vuelidate/lib/validators';
    import {isCorrectHumanName, isValidRegistrationBirthDay, notHaveSpace} from '../../validators/validators.js';

    export default {
        name: 'AccountSettingsMain',
        computed: {
            userData() {
                return this.$root.$auth.user;
            },
            geoLocations() {
                let geolocation = [];

                if (!this.locations) return;

                this.locations.forEach((item) => {
                    geolocation.push({
                        id: item.id,
                        title: {
                            ru: item.title.ru,
                            ua: item.title.ua,
                            en: item.title.en,
                        },
                        region: item.region,
                        country: item.country,
                    });
                });

                return geolocation;
            },
            isSuccessFirstName() {
                return (!this.$v.model.firstName.$invalid || !(!!this.serverRegMessages.firstName)) && !
                  !this.model.firstName;
            },
            firstNameError() {
                if (this.$v.model.firstName.$error) {
                    if (!this.$v.model.firstName.required) {
                        return 'Укажите как Вас зовут.';
                    } else if (!this.$v.model.firstName.minLength) {
                        return 'Врядли у Вас такое короткое имя?';
                    } else if (!this.$v.model.firstName.maxLength) {
                        return 'Слишком длинное имя.';
                    } else if (!this.$v.model.firstName.isCorrectHumanName) {
                        return 'Только буквы в имени.';
                    } else if (!this.$v.model.firstName.notHaveSpace) {
                        return 'Не должно быть пробелов.';
                    }
                } else if (this.serverRegMessages.firstName) {
                    return this.serverRegMessages.firstName;
                }

                return null;
            },
            isSuccessLastName() {
                return (!this.$v.model.lastName.$invalid || !(!!this.serverRegMessages.lastName)) &&
                  !!this.model.lastName;
            },
            lastNameError() {
                if (this.$v.model.lastName.$error) {
                    if (!this.$v.model.lastName.required) {
                        return 'Укажите свою фамилию.';
                    } else if (!this.$v.model.lastName.minLength) {
                        return 'Врядли у Вас такая короткая фамилия.';
                    } else if (!this.$v.model.lastName.maxLength) {
                        return 'Слишком длинная фамилия.';
                    } else if (!this.$v.model.lastName.isCorrectHumanName) {
                        return 'Только буквы в фамилии.';
                    } else if (!this.$v.model.lastName.notHaveSpace) {
                        return 'Не должно быть пробелов.';
                    }
                } else if (this.serverRegMessages.lastName) {
                    return this.serverRegMessages.lastName;
                }

                return null;
            },
            isSuccessBirthday() {
                return (!this.$v.model.birthday.$invalid || !(!!this.serverRegMessages.birthday)) &&
                  !!this.model.birthday;
            },
            birthdayError() {
                if (this.$v.model.birthday.$error) {
                    if (!this.$v.model.birthday.isValidBirthday) {
                        return 'Укажите корректную дату.';
                    }
                } else if (this.serverRegMessages.birthday) {
                    return this.serverRegMessages.birthday;
                }

                return null;
            },
            canEditRelationUser() {
                let id = Number(this.model.relationshipId);

                return !!(id === 1 || id === 4 || id === 5);
            },
        },
        data() {
            return {
                model: {
                    firstName: this.$root.$auth.user.profile.firstName,
                    lastName: this.$root.$auth.user.profile.lastName,
                    sex: this.$root.$auth.user.profile.sex,
                    relationshipId: this.$root.$auth.user.profile.relationshipId,
                    relationshipUser: this.$root.$auth.user.profile.relationshipUser,
                    birthday: this.$root.$auth.user.profile.birthday,
                    location: this.$root.$auth.user.profile.location,
                },
                isEdit: {
                    firstName: false,
                    lastName: false,
                    birthday: false,
                    location: false,
                },
                isSend: {
                    firstName: false,
                    lastName: false,
                    sex: false,
                    birthday: false,
                    relationshipId: false,
                    location: false,
                },
                locations: [],
                friends: [],
                serverRegMessages: {
                    firstName: null,
                    lastName: null,
                    birthday: null,
                },
            }
        },
        validations() {
            return {
                model: {
                    firstName: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(50),
                        isCorrectHumanName,
                        notHaveSpace,
                    },
                    lastName: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(50),
                        isCorrectHumanName,
                        notHaveSpace,
                    },
                    birthday: {
                        isValidBirthday: (value) => {
                            if (!!value) {
                                return isValidRegistrationBirthDay(value);
                            }

                            return true;
                        },
                    },
                }
            };
        },
        methods: {
            getRef(refKey) {
                for (let [key, value] of Object.entries(this.$refs)) {
                    if (refKey === key)
                        return value;
                }
                return null;
            },
            startFieldEdit(fieldName) {
                this.isEdit[fieldName] = true;

                const inpRef = this.getRef(fieldName);

                if (inpRef) {
                    inpRef.focus();
                } else {
                    window.console.warn(`Ошибка редактирования поля`);
                }
            },
            finishFieldEdit(fieldName) {
                const inpRef = this.getRef(fieldName);
                this.$v.model[fieldName].$touch();

                setTimeout(() => {
                    this.isEdit[fieldName] = false;

                    if (inpRef) {
                        inpRef.blur();

                        if ((fieldName === 'birthday') && this.model[fieldName] === '') {
                            this.model[fieldName] = this.$root.$auth.user.profile[fieldName];
                            return;
                        }

                        if (!this.isSend[fieldName])
                            this.accountStartSaveData(this.model[fieldName], fieldName);
                    } else {
                        window.console.warn(`Ошибка редактирования поля`);
                    }
                }, 100);
            },
            formatFormData(newValue, fieldName) {
                let formData = {};

                if (fieldName === 'location') {
                    formData = {geoCityId: newValue.id};
                } else if (fieldName === 'relationshipUsers') {
                    formData = {relationshipUserId: newValue.id};
                } else {
                    for (let prop in formData) {
                        if (prop !== 'birthday') {
                            formData[prop] = formData[prop].trim();
                        }
                    }

                    if (fieldName === 'relationshipId' && newValue === 'null') {
                        newValue = null;
                    }

                    formData[fieldName] = newValue;
                }

                return formData;
            },
            inputFieldEdit($event, fieldName) {
                if (fieldName === 'birthday') {
                    this.model.birthday = $event.target.value;
                }

                this.serverRegMessages[fieldName] = null;
                this.$v.model[fieldName].$touch();
            },
            locationLabel({title, region, country}) {
                if (title) {
                    if (region) {
                        return `${country.title.ru}, ${region ? region.title.ru : null}, ${title.ru}`;
                    }

                    return `${country.title.ru}, ${title.ru}`;
                }
            },
            relationshipUserLabel({id, title, profile}) {
                if (id === null) {
                    return title;
                }

                return `${profile.firstName} ${profile.lastName}`;
            },

            async accountStartSaveData(newValue, fieldName) {
                this.isSend[fieldName] = true;

                if (!!this[`${fieldName}Error`]) {
                    this.model[fieldName] = this.$root.$auth.user.profile[fieldName];
                    return;
                }

                this.isEdit[fieldName] = false;

                let formData = this.formatFormData(newValue, fieldName);
                let response = null;

                try {
                    response = await this.$root.$api.updateUser(formData);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response !== null) {
                    this.$root.$auth.user.updateAuthUser({profile: response});
                    this.$root.$auth.storeUserData();

                    if (fieldName === `firstName` || fieldName === `lastName`) {
                        this.$root.$emit('updateUserName', {
                            firstName: this.$root.$auth.user.profile.firstName,
                            lastName: this.$root.$auth.user.profile.lastName,
                        });
                    }

                    if (fieldName === 'relationshipId') {
                        if (newValue == 2 || newValue == 3) {
                            this.model.relationshipUser = response.relationshipUser;
                        }
                    }

                    setTimeout(() => {
                        this.isSend[fieldName] = false;
                    }, 2000);
                }
            },
            async getLocations(location) {
                let response;

                try {
                    response = await this.$root.$api.getLocationsByInput(location);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.locations = response;
                }
            },
            async getFriends(limit = 50, offset = 0) {
                let response;

                try {
                    response = await this.$root.$api.$friend.friendsList(this.userData.id, limit, offset);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.friends.push({
                       id: null,
                       title: 'Не указывать',
                    });
                    response.forEach((friend) => {
                        this.friends.push(friend);
                    });
                }
            },
        },
        mounted() {
            this.getFriends();
        },
    }
</script>
