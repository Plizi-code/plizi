<template>
    <div id="communitySettingsAdditional"
         class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <div class="plz-account-settings-form pb-0 px-3 mb-0">
            <div class="plz-account-settings-header row border-bottom">
                <div class="d-flex">
                    <h6 class="title-settings mb-0">Дополнительная информация</h6>
                </div>
            </div>

            <div class="plz-account-settings-body ">

                <div class="form-group row border-bottom">
                    <label for="privacy"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Тип сообщества
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative">
                            <i v-if="Number(model.privacy) !== 1" class="fas fa-lock mr-2"></i>
                            <i v-else class="fas fa-unlock mr-2"></i>
                            <div class="w-100 position-relative ml-n2">
                                <select id="privacy"
                                        class="form-control border-0 pl-2"
                                        @change="accountStartSaveData(model.privacy, 'privacy')"
                                        v-model="model.privacy">
                                    <option v-for="item in privacyList" :key="item.value" :value="item.value">
                                        {{item.title}}
                                    </option>
                                </select>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="themeId"
                           class="plz-account-settings-body-label col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        Тематика сообщества
                    </label>
                    <div class="plz-account-settings-body-field col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="d-flex align-items-center w-100 position-relative">
                            <div class="w-100 position-relative ml-n2">
                                <select v-model="model.themeId" id="themeId" ref="themeId"
                                        @change="accountStartSaveData(model.themeId, 'themeId')"
                                        class="form-control border-0 pl-2">
                                    <option value="0" disabled>Выберите тематику</option>
                                    <optgroup v-for="theme in themes" :key="theme.id" :label="theme.name">
                                        <option v-for="child in theme.children" :value="child.id">{{child.name}}
                                        </option>
                                    </optgroup>
                                </select>
                                <i class="fas fa-chevron-down"></i>
                                <i class="fas fa-chevron-down px-2 d-flex align-items-center h-100 "></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="form-group row border-bottom">
                    <label for="website"
                           class="plz-account-settings-body-label col-6 col-sm-4 col-lg-4 ">
                        Сайт
                    </label>
                    <div class="plz-account-settings-body-field order-1 order-sm-0 col-12 col-sm-5 col-lg-6 ">
                        <input type="text"
                               id="website"
                               class="w-100 w-sm-75"
                               v-model="model.website"
                               :class="[isEdit.website ? 'form-control' : 'form-control-plaintext', {
                                   'is-invalid': $v.model.website.$error || serverRegMessages.website,
                                    'is-valid': isSuccessWebsite
                               }]"
                               @input="inputFieldEdit($event, 'website')"
                               @keyup.enter="clickField('website')"
                               @blur="clickField('website')"
                               :readonly="!isEdit.website"
                               ref="website">

                        <div class="invalid-feedback w-100" v-if="$v.model.website.$error || serverRegMessages.website">
                            <p class="text-danger" v-if="!$v.model.website.or">Укажите корректный адрес сайта сообщества.</p>
                            <p class="text-danger" v-else-if="serverRegMessages.website">{{ serverRegMessages.website }}</p>
                        </div>

                    </div>
                    <div class="plz-account-settings-body-action col-6 col-sm-3 col-lg-2 d-flex">
                        <button type="button"
                                class="btn btn-link"
                                :class="{'text-primary': isEdit.website}"
                                @click="clickField('website')">
                            {{ isEdit.website ? 'Сохранить' : 'Изменить' }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import {url, or} from 'vuelidate/lib/validators';
    import PliziCommunity from "../../classes/PliziCommunity.js";
    import communityUtils from "../../utils/CommunityUtils.js";
    import EditInline from "../../mixins/EditInline.js";
    import {isCorrectUrl} from '../../validators/validators.js';

    export default {
        name: 'CommunitySettingsAdditional',
        props: {
            community: PliziCommunity,
        },
        mixins: [
            EditInline,
        ],
        computed: {
            isSuccessWebsite() {
                return (!this.$v.model.website.$invalid || !(!!this.serverRegMessages.website)) && !
                    !this.model.website;
            },
        },
        data() {
            return {
                model: {
                    privacy: this.community.privacy,
                    themeId: this.community.themeId,
                    website: this.community.website,
                },
                isEdit: {
                    website: false,
                },
                isSend: {
                    website: false,
                },
                serverRegMessages: {
                    website: null,
                },
                themes: [],
                privacyList: communityUtils.privacyList,
                types: communityUtils.types,
            }
        },
        async mounted() {
            let apiResponse = null;

            this.popularCommunities = null;

            try {
                apiResponse = await this.$root.$api.$communities.getThemes();
            } catch (e) {
                window.console.warn(e.detailMessage);
                throw e;
            }

            this.themes = apiResponse.data;
        },
        validations() {
            return {
                model: {
                    website: {
                        or: or(url, (value) => value === '', isCorrectUrl),
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
        },
    }
</script>
