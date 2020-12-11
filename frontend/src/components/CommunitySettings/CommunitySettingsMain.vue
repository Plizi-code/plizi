<template>
    <div id="communitySettingsMain"
         class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <div class="plz-account-settings-form pb-0 px-3 mb-0">
            <div class="plz-account-settings-header row border-bottom">
                <div class="d-flex">
                    <h6 class="title-settings mb-0">Основные</h6>
                </div>
            </div>

            <div class="plz-account-settings-body ">

                <div class="form-group row border-bottom">
                    <label for="name"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Название
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <input type="text"
                               id="name"
                               class="w-100 w-sm-75"
                               v-model="model.name"
                               :class="[isEdit.name ? 'form-control' : 'form-control-plaintext', {
                                   'is-invalid': ($v.model.name.$error || serverRegMessages.name),
                                    'is-valid': isSuccessName
                               }]"
                               @input="inputFieldEdit($event, 'name')"
                               @keyup.enter="clickField(`name`)"
                               @blur="clickField('name')"
                               :readonly="!isEdit.name"
                               ref="name">

                        <div class="invalid-feedback" v-if="$v.model.name.$error || serverRegMessages.name">
                            <p class="text-danger" v-if="!$v.model.name.required">Укажите название сообщества.</p>
                            <p class="text-danger" v-else-if="!$v.model.name.minLength">
                                Врядли у Вас такое короткое название?
                            </p>
                            <p class="text-danger" v-else-if="!$v.model.name.maxLength">Слишком длинное название.</p>
                            <p class="text-danger" v-else-if="serverRegMessages.name">{{ serverRegMessages.name }}</p>
                        </div>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.name}"
                                @click="clickField('name')">
                            {{ isEdit.name ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="notice"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Короткое описание
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <input type="text"
                               id="notice"
                               class="w-100 w-sm-75"
                               v-model="model.notice"
                               :class="[isEdit.notice ? 'form-control' : 'form-control-plaintext', {
                                   'is-invalid': ($v.model.notice.$error || serverRegMessages.notice),
                                    'is-valid': isSuccessNotice
                               }]"
                               @input="inputFieldEdit($event, 'notice')"
                               @keyup.enter="clickField('notice')"
                               @blur="clickField('notice')"
                               :readonly="!isEdit.notice"
                               ref="notice">

                        <div class="invalid-feedback" v-if="$v.model.notice.$error || serverRegMessages.notice">
                            <p class="text-danger" v-if="!$v.model.notice.maxLength">Слишком длинное название.</p>
                            <p class="text-danger" v-else-if="serverRegMessages.notice">{{ serverRegMessages.notice }}</p>
                        </div>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.notice}"
                                @click="clickField('notice')">
                            {{ isEdit.notice ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="description"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Описание
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <textarea type="text"
                                  id="description"
                                  class="w-100 w-sm-75"
                                  :class="[isEdit.description ? 'form-control' : 'form-control-plaintext', {
                                      'is-invalid': ($v.model.description.$error || serverRegMessages.description),
                                      'is-valid': isSuccessDescription
                                  }]"
                                  v-model="model.description"
                                  placeholder="Добавьте описание"
                                  @blur="clickField(`description`)"
                                  :readonly="!isEdit.description"
                                  ref="description">
                        </textarea>

                        <div class="invalid-feedback" v-if="$v.model.description.$error || serverRegMessages.description">
                            <p class="text-danger" v-if="!$v.model.description.maxLength">Слишком длинное название.</p>
                            <p class="text-danger" v-else-if="serverRegMessages.description">{{ serverRegMessages.description }}</p>
                        </div>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.description}"
                                @click="clickField('description')">
                            {{ isEdit.description ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="headerImage"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Обложка сообщества
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">

                        <label for="headerImage" class="community-primary-image mr-3 cursor-pointer">
                            <img ref="headerImage" :src="headerImage" :alt="community.name"
                                 class="img-thumbnail rounded" style="width: 242px;height: 63px;"/>
                        </label>

                        <input id="headerImage" ref="headerImage" type="file"
                               @change="uploadImage()"
                               class="d-none"/>

                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="url"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Адрес страницы
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">

                        <div class="input-group-prepend plz-prepend-w140 ">
                            <span class="input-group-text">http://plizi.com/</span>
                        </div>
                        <input type="text"
                               id="url"
                               class="px-1"
                               v-model="model.url"
                               :class="[isEdit.url ? 'form-control' : 'form-control-plaintext', {
                                   'is-invalid': $v.model.url.$error || serverRegMessages.url,
                                    'is-valid': isSuccessUrl
                               }]"
                               @input="inputFieldEdit($event, 'url')"
                               @keyup.enter="clickField('url')"
                               @blur="clickField('url')"
                               :readonly="!isEdit.url"
                               ref="url">

                        <div class="invalid-feedback w-100" v-if="$v.model.url.$error || serverRegMessages.url">
                            <p class="text-danger" v-if="!$v.model.url.maxLength">Слишком длинный адрес.</p>
                            <p class="text-danger" v-else-if="!$v.model.url.isCorrectSlug">
                                Только латиница, цифры, подчеркивание и дефис (первая буква).
                            </p>
                            <p class="text-danger" v-else-if="serverRegMessages.url">{{ serverRegMessages.url }}</p>
                        </div>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.url}"
                                @click="clickField('url')">
                            {{ isEdit.url ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

                <div class="form-group row border-bottom">
                    <div class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Верификация
                    </div>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <a href="#" @click.stop="onRequestClick">Подать заявку</a>
                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
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

            </div>
        </div>
    </div>
</template>

<script>
import {required, minLength, maxLength} from 'vuelidate/lib/validators';
import PliziCommunity from "../../classes/PliziCommunity";
import {isCorrectSlug} from '../../validators/validators.js';
import PliziCommunityAvatar from "../../classes/Community/PliziCommunityAvatar";
import EditInline from "../../mixins/EditInline.js";

export default {
    name: 'CommunitySettingsMain',
    props: {
        community: PliziCommunity,
    },
    mixins: [
        EditInline,
    ],
    computed: {
        headerImage() {
            return this.community.headerImage?.image.thumb.path || '/images/community-header-bg.jpg';
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

        isSuccessName() {
            return (!this.$v.model.name.$invalid || !(!!this.serverRegMessages.name)) && !
                !this.model.name;
        },
        isSuccessNotice() {
            return (!this.$v.model.notice.$invalid || !(!!this.serverRegMessages.notice)) && !
                !this.model.notice;
        },
        isSuccessDescription() {
            return (!this.$v.model.description.$invalid || !(!!this.serverRegMessages.description)) && !
                !this.model.description;
        },
        isSuccessUrl() {
            return (!this.$v.model.url.$invalid || !(!!this.serverRegMessages.url)) && !
                !this.model.url;
        },
    },
    data() {
        return {
            model: {
                name: this.community.name,
                notice: this.community.notice || '',
                description: this.community.description || '',
                url: this.community.url,
                location: this.community.location,
            },
            isEdit: {
                name: false,
                notice: false,
                description: false,
                url: false,
                location: false,
            },
            isSend: {
                name: false,
                notice: false,
                description: false,
                url: false,
                location: false,
            },
            serverRegMessages: {
                name: null,
                notice: null,
                description: null,
                url: null,
            },
            locations: [],
        }
    },
    validations() {
        return {
            model: {
                name: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(100),
                },
                notice: {
                    maxLength: maxLength(100),
                },
                description: {
                    maxLength: maxLength(1000),
                },
                url: {
                    maxLength: maxLength(100),
                    isCorrectSlug,
                },
            }
        };
    },
    methods: {
        showErrorOnLargeFile() {
            this.$alert(`<h4 class="text-white">Ошибка</h4>
        <div class="alert alert-danger">
            Превышен максимальный размер файла.
            <br />
            Максимальный размер файла:
            <b class="text-success">2 MB</b>
        </div>`,
             `bg-danger`,
             30
            );
        },
        formatFormData(newValue, fieldName) {
            let formData = {};
            if (fieldName === 'location') {
                formData = {location: newValue.id};
            } else {
                formData[fieldName] = newValue;
            }
            return formData;
        },
        locationLabel({title, region, country}) {
            if (title) {
                if (region) {
                    return `${country.title.ru}, ${region ? region.title.ru : null}, ${title.ru}`;
                }

                return `${country.title.ru}, ${title.ru}`;
            }
        },
        async accountStartSaveData(newValue, fieldName) {
            this.isSend[fieldName] = true;
            if (!!this[`${fieldName}Error`]) {
                this.model[fieldName] = this.community[fieldName];
                return;
            }

            let formData = this.formatFormData(newValue, fieldName);
            let response = null;

            try {
                response = await this.$root.$api.$communities.update(this.community.id, formData);
            } catch (e) {
                console.warn(e.detailMessage);
                if (e.status === 422) {
                    const inpRef = this.getRef(fieldName);

                    if (inpRef) {
                        inpRef.focus();
                    }
                    this.isEdit[fieldName] = true;
                    this.serverRegMessages[fieldName] = e.data?.errors[fieldName][0];
                }
            }

            setTimeout(() => {
                this.isSend[fieldName] = false;
            }, 200);
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

        /**
         * @returns {boolean|FormData}
         */
        getFileFormData() {
            const fName = this.$refs.headerImage.value;
            const fExt = fName.split('.').pop().toLowerCase();
            const allowExts = ['png', 'jpg', 'jpeg', 'bmp', 'webp', 'gif'];

            if (!allowExts.includes(fExt)) {
                this.$alert(`<h4 class="text-white">Ошибка</h4>
<div class="alert alert-danger">
Недопустимое расширение у файла <b>${fName}</b><br />
Допустимы только: <b class="text-success">${allowExts.join(', ')}</b>
</div>`, `bg-danger`, 30);
                return false;
            }

            const formData = new FormData();
            formData.append('file', this.$refs.headerImage.files[0]);
            formData.append('id', this.community.id);
            this.$refs.headerImage.value = '';

            return formData;
        },

        async uploadImage() {
            const formData = this.getFileFormData();

            if (!formData) {
                return;
            }

            const {size} = formData.get('file');

            if (size > 2000000) {
                this.showErrorOnLargeFile();
                return;
            }

            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$communities.updateHeaderImage(formData);
            } catch (e) {
                if (e.status === 422) {
                    this.showErrorOnLargeFile();
                    return;
                }

                window.console.warn(e.detailMessage);
            }

            if (apiResponse) {
                this.community.headerImage = new PliziCommunityAvatar(apiResponse.data);
            }
        },

        onRequestClick() {
            this.$root.$alert(`Заявка`, 'bg-info', 3);
        }
    },
}
</script>
