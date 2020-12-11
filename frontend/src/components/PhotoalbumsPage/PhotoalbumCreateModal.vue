<template>
    <div class="modal community-create-modal" id="communityCreateModal"
         tabindex="-1" role="dialog" aria-labelledby="communityCreateModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hidePhotoalbumCreateModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20 overflow-auto">

                <div class="modal-body p-0 pt-3 w-100">
                    <div class="community-create-modal-box p-4 w-100 overflow-hidden">
                        <h5 class="community-create-modal-title text-center mb-2">Создать альбом</h5>

                        <div class="d-flex align-items-center justify-content-center">
                            <img src="images/icons/icon-communities.png" alt="image">
                        </div>

                        <p class="community-create-modal-desc text-center mb-4 mt-3">
                            Создайте альбом для своих фотографий
                        </p>
                    </div>

                    <form class="form community-create-modal-form p-0  w-100 overflow-hidden"
                          @submit.prevent="startCreatePhotoalbum" novalidate="novalidate">

                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="photoTitle" class="col-4 community-create-modal-label  text-right">Название:</label>
                            <div class="col-8">
                                <input v-model="model.title"
                                       type="text"
                                       id="photoTitle"
                                       ref="photoTitle"
                                       @blur="$v.model.title.$touch()"
                                       class="form-control community-create-modal-select"
                                       required/>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.title.$error">
                                <p v-if="!$v.model.title.required" class="text-danger">Укажите название
                                    альбома</p>
                                <p v-if="!$v.model.title.minLength" class="text-danger">Название не может быть короче
                                    <b>четырёх</b> символов</p>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="photoDescription" class="col-4 community-create-modal-label  text-right">Описание:</label>
                            <div class="col-8">
                                <input v-model="model.description"
                                       type="text"
                                       id="photoDescription"
                                       ref="photoDescription"
                                       @blur="$v.model.description.$touch()"
                                       class="form-control community-create-modal-select"/>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-8 invalid-feedback" v-if="$v.model.description.$error">
                                <p v-if="!$v.model.description.isValidDescription" class="text-danger">Описание не может быть короче
                                    <b>четырёх</b> символов</p>
                            </div>
                        </div>

                        <div
                            class="form-group d-flex flex-column align-items-center px-5 py-4 community-create-modal-footer mb-0 row">
                            <div class="col-12 col-md-5 d-flex align-items-center justify-content-end px-0 mx-auto">
                                <button type="submit"
                                        class="btn w-100 btn-primary rounded-pill">
                                    Создать альбом
                                </button>
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

export default {
    name: 'PhotoalbumCreateModal',
    props: {},
    data() {
        return {
            model: {
                title: '',
                description: ''
            },
        }
    },
    validations() {
        return {
            model: {
                title: {
                    required,
                    minLength: minLength(4)
                },
                description: {
                    isValidDescription(value) {
                        if (!!value) {
                            return value.length >= 4;
                        }

                        return true;
                    },
                },
            },
        };
    },
    methods: {
        hidePhotoalbumCreateModal() {
            this.$emit('HidePhotoalbumCreateModal', {});
        },

        startCreatePhotoalbum() {
            this.$v.$touch();
            if (!this.$v.model.$invalid)
                this.createPhotoalbum();
        },

        async createPhotoalbum() {
            let apiResponse = null;

            let formData = {
                title: this.model.title.trim(),
                description: this.model.description.trim()
            };

            try {
                apiResponse = await this.$root.$api.$photoalbums.createPhotoalbum(formData);
            } catch (e) {
                console.warn(e.detailMessage);
            }

            if (apiResponse) {
                formData.id = apiResponse.id;
                this.$root.$emit('onAddPhotoAlbum', formData);
                this.hidePhotoalbumCreateModal();
                this.$notify('Альбом успешно создан.');
            }
        }
    },
    mounted() {
        setTimeout(() => {
            this.$refs.photoTitle.focus();
        }, 100);
    },
}
</script>

<style scoped>
    .invalid-feedback {
        display: block;
    }
</style>
