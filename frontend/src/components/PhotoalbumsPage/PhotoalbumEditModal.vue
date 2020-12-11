<template>
    <div class="modal community-create-modal" id="communityCreateModal"
         tabindex="-1" role="dialog" aria-labelledby="communityCreateModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="onHide">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20 overflow-auto">

                <div class="modal-body p-0 pt-3 w-100">
                    <div class="community-create-modal-box p-4 w-100 overflow-hidden">
                        <h5 class="community-create-modal-title text-center mb-2">Редактировать альбом</h5>

                        <div class="d-flex align-items-center justify-content-center">
                            <img src="images/icons/icon-communities.png" alt="image">
                        </div>

                        <p class="community-create-modal-desc text-center mb-4 mt-3">
                            Редактировать альбом
                        </p>
                    </div>

                    <form class="form community-create-modal-form p-0  w-100 overflow-hidden"
                          @submit.prevent="startEditPhotoAlbum" novalidate="novalidate">

                        <div class="form-group d-flex align-items-center mb-4 px-5 row position-relative">
                            <label for="photoTitle" class="col-4 community-create-modal-label  text-right">Название:</label>
                            <div class="col-8">
                                <input v-model="model.title"
                                       type="text"
                                       id="photoTitle"
                                       ref="commName"
                                       @input="onInput('title')"
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
                                       ref="commName"
                                       @input="onInput('description')"
                                       @blur="$v.model.description.$touch()"
                                       class="form-control community-create-modal-select"
                                       required/>
                            </div>
                            <div class="col-4"></div>
                            <div v-if="$v.model.description.$error"
                                 class="col-8 invalid-feedback">
                                <p v-if="!$v.model.description.isValidDescription" class="text-danger">
                                    Укажите описание альбома
                                </p>
                            </div>
                        </div>

                        <div
                            class="form-group d-flex align-items-center px-5 py-4 community-create-modal-footer mb-0 row">
                            <div class="col-12 col-md-5 d-flex flex-column align-items-center justify-content-end px-0 mx-auto">
                                <button type="submit"
                                        :disabled="isShipped"
                                        class="btn w-100 btn-primary rounded-pill">
                                    Сохранить изменения
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
import PliziPhotoAlbum from "../../classes/PliziPhotoAlbum.js";

export default {
    name: 'PhotoalbumEditModal',
    props: {
        photoAlbum: PliziPhotoAlbum,
    },
    data() {
        return {
            model: {
                title: this.photoAlbum.title,
                description: this.photoAlbum.description,
            },
            isShipped: false,
            errors: null,
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
            }
        };
    },
    methods: {
        onHide() {
            this.$emit('HidePhotoalbumEditModal', {});
        },
        startEditPhotoAlbum() {
            this.$v.$touch();

            if (!this.$v.$invalid) {
                this.editPhotoAlbum();
            }
        },
        onInput(fieldName) {
            if (this.errors && this.errors[fieldName]) {
                this.errors[fieldName] = null;
            }
        },

        async editPhotoAlbum() {
            this.isShipped = true;
            this.errors = null;
            let apiResponse = null;
            let formData = {
                title: this.model.title.trim(),
                description: this.model.description ? this.model.description.trim() : null,
            };

            try {
                apiResponse = await this.$root.$api.$photoalbums.updatePhotoAlbum(this.photoAlbum.id, formData);
            } catch (e) {
                this.isShipped = false;

                if (e.status === 422) {
                    this.errors = e.data.errors;
                }

                console.warn(e.detailMessage);
            }

            if (apiResponse) {
                this.$root.$emit('onUpdate', {
                    title: this.model.title,
                    description: this.model.description,
                });
                this.onHide();
                this.$notify('Альбом успешно обновлен.');
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
