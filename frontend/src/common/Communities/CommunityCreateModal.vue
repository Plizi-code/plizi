<template>
    <div class="modal community-create-modal" id="communityCreateModal"
         tabindex="-1" role="dialog" aria-labelledby="communityCreateModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hideCommunityCreateModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20 overflow-auto">

                <div class="modal-body p-0 pt-3 w-100">
                    <div class="community-create-modal-box p-4 w-100 overflow-hidden">
                        <h5 class="community-create-modal-title text-center mb-2">Создать сообщество</h5>

                        <div class="d-flex align-items-center justify-content-center">
                            <img src="images/icons/icon-communities.png" alt="image">
                        </div>

                        <p class="community-create-modal-desc text-center mb-4 mt-3">
                            Публикуйте материалы разных форматов, общайтесь с читателями, изучайте статистику и
                            подключайте монетизацию.<br/>
                            Для начала выберите тип сообщества.
                        </p>
                    </div>

                    <form class="form community-create-modal-form p-0  w-100 overflow-hidden"
                          @submit.prevent="" novalidate="novalidate">
                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="commType" class="col-4 community-create-modal-label text-right">Тип
                                сообщества:</label>
                            <div class="col-8">
                                <select v-model="model.type"
                                        id="commType" ref="commType" @click="$v.model.type.$touch()"
                                        class="form-control  community-create-modal-select">
                                    <option value="0" disabled>Выберите тип</option>
                                    <option v-for="item in types" :key="item.value" :value="item.value">
                                        {{item.title}}
                                    </option>
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.type.$error">
                                <p v-if="!$v.model.type.required" class="text-danger">Укажите тип сообщества</p>
                                <p v-if="!$v.model.type.isIn" class="text-danger">Выберите тип сообщества</p>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="commName" class="col-4 community-create-modal-label  text-right">Название:</label>
                            <div class="col-8">
                                <input v-model="model.name" type="text" id="commName" ref="commName"
                                       @blur="$v.model.name.$touch()"
                                       class="form-control community-create-modal-select" required/>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.name.$error">
                                <p v-if="!$v.model.name.required" class="text-danger">Укажите название
                                    сообщества</p>
                                <p v-if="!$v.model.name.minLength" class="text-danger">Название не может быть короче
                                    <b>четырёх</b> символов</p>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="commTema"
                                   class="col-4 community-create-modal-label  text-right">Тематика:</label>
                            <div class="col-8">
                                <select v-model="model.themeId" id="commTema" ref="commType"
                                        @click="$v.model.themeId.$touch()"
                                        class="form-control  community-create-modal-select">
                                    <option value="0" disabled>Выберите тематику</option>
                                    <optgroup v-for="theme in themes" :key="theme.id" :label="theme.name">
                                        <option v-for="child in theme.children" :value="child.id">{{child.name}}
                                        </option>
                                    </optgroup>
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.themeId.$error">
                                <p v-if="!$v.model.themeId.required" class="text-danger">Укажите тематику
                                    сообщества</p>
                                <p v-if="!$v.model.themeId.isIn" class="text-danger">Выберите тематику
                                    сообщества</p>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-center mb-5 px-5 row position-relative">
                            <label for="commPrivacy"
                                   class="col-4 community-create-modal-label  text-right">Приватность:</label>
                            <div class="col-8">
                                <select v-model="model.privacy" id="commPrivacy"
                                        ref="commPrivacy" @click="$v.model.privacy.$touch()"
                                        class="form-control community-create-modal-select">
                                    <option value="0" disabled>Выберите уровень приватности</option>
                                    <option v-for="item in privacyList" :key="item.value" :value="item.value">
                                        {{item.title}}
                                    </option>
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.privacy.$error">
                                <p v-if="!$v.model.privacy.required" class="text-danger">Укажите уровень приватности
                                    сообщества</p>
                                <p v-if="!$v.model.privacy.isIn" class="text-danger">Выберите уровень приватности
                                    сообщества</p>
                            </div>
                        </div>

                        <div
                            class="form-group d-flex align-items-center px-5 py-4 community-create-modal-footer mb-0 row">
                            <label for="communityRules" class="col-12 col-md-7 radio mb-3 mb-md-0">
                                <input class="mb-0"
                                       v-model="model.rule"
                                       type="checkbox" name="communityRules"
                                       ref="communityRules" id="communityRules"/>
                                <span class="mb-0">Я прочитал и согласен с
                                    <router-link class="text-link" :to="{name: 'CommunityRulesPage'}" target="_blank">правилами</router-link>
                                </span>
                            </label>
                            <div class="col-12 col-md-5 d-flex align-items-center justify-content-end px-0">
                                <button @click.stop="startCreateCommunity()" type="button"
                                        :disabled="!model.rule"
                                        class="btn w-100 btn-primary rounded-pill">Создать сообщество
                                </button>
                            </div>
                            <div class="col-7 invalid-feedback" v-if="$v.model.rule.$error">
                                <p v-if="!$v.model.rule.selected" class="text-danger">Вы должны согласиться с
                                    правилами</p>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {minLength, required} from "vuelidate/lib/validators";
import communityUtils from "../../utils/CommunityUtils"

export default {
    name: 'CommunityCreateModal',
    components: {},
    props: {},
    data() {
        return {
            model: {
                type: 0,
                name: '',
                themeId: 0,
                privacy: 0,
                rule: false,
            },
            themes: null,
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
                name: {
                    required,
                    minLength: minLength(4)
                },
                type: {
                    required,
                    isIn(value) {
                        return this.types.reduce((acc, current) => {
                            return acc || current.value === value;
                        }, false);
                    },
                },
                themeId: {
                    required,
                    isIn(value) {
                        return value > 0;
                    },
                },
                privacy: {
                    required,
                    isIn(value) {
                        return this.privacyList.reduce((acc, current) => {
                            return acc || current.value === value;
                        }, false);
                    },
                },
                rule: {
                    selected(value) {
                        return value;
                    }
                }
            }
        };
    },
    methods: {
        hideCommunityCreateModal() {
            this.$emit('HideCommunityCreateModal', {});
        },

        startCreateCommunity() {
            this.$v.$touch();
            if (!this.$v.model.$invalid)
                this.createCommunity();
        },

        async createCommunity() {
            let apiResponse = null;

            let formData = {
                type: this.model.type,
                name: this.model.name.trim(),
                theme_id: this.model.themeId,
                privacy: this.model.privacy,
            };

            try {
                apiResponse = await this.$root.$api.$communities.communityCreate(formData);
                this.hideCommunityCreateModal();
            } catch (e) {
                console.warn(e.detailMessage);
            }

            if (apiResponse) {
                this.$emit('AddNewCommunity', apiResponse);
                this.$router.push({name: 'CommunityPage', params: {id: apiResponse.id}})
            }
        }
    },
}
</script>

<style scoped>
    .invalid-feedback {
        display: block;
    }
</style>
