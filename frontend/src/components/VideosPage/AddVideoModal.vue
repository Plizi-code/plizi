<template>
    <div class="modal" id="chatVideoModal" tabindex="-1" role="dialog" aria-labelledby="chatVideoModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="onHide">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <form class="p-5" @submit.prevent="store">
                    <div class="form-group">
                        <label for="link">Ссылка на видео</label>
                        <input type="text"
                               class="form-control"
                               :class="{ 'is-invalid': isLinkError}"
                               @input="onInput('link')"
                               @blur="$v.form.link.$touch()"
                               id="link"
                               ref="videoLink"
                               v-model="form.link">

                        <div v-if="isLinkError"
                             class="invalid-feedback">
                            <p v-if="this.errors && this.errors.link">
                                {{ this.errors.link[0] }}
                            </p>
                            <p v-else-if="!this.$v.form.link.required">
                                Поле Ссылка на видео обязательно для заполнения.
                            </p>
                            <p v-else-if="!this.$v.form.link.url">
                                Значение поле Ссылка на видео не является корректной ссылкой.
                            </p>
                            <p v-else-if="!this.$v.form.link.isValidYoutubeLink">
                                Значение поле Ссылка на видео не является корректной ссылкой на сервис youtube.
                            </p>
                        </div>
                    </div>
                    <button type="submit"
                            class="btn plz-btn plz-btn-primary"
                            :disabled="$v.$invalid">
                        Сохранить
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {required, url} from 'vuelidate/lib/validators';
import {isValidYoutubeLink} from '../../validators/validators.js';

import LinkMixin from "../../mixins/LinkMixin.js";

export default {
name: "AddVideoModal",
mixins: [LinkMixin],
data() {
    return {
        form: {
            link: null,
        },
        errors: null,
        isStoreRequest: false,
    }
},

validations() {
    return {
        form: {
            link: {
                required,
                url,
                isValidYoutubeLink,
            },
        },
    }
},

computed: {
    isLinkError() {
        return (this.errors && this.errors.link) || this.$v.form.link.$error;
    },
},

methods: {
    onHide() {
        this.$emit('onHide');
    },
    onInput(fieldName) {
        if (this.errors && this.errors[fieldName]) {
            this.errors[fieldName] = null;
        }
    },

    async store() {
        if (this.isStoreRequest || this.$v.$invalid) {
            return ;
        }
        this.isStoreRequest = true;
        this.errors = null;

        let response;
        let formData = {
            link: this.form.link
        };

        try {
            response = await this.$root.$api.$video.storeVideo(formData);
        } catch (e) {
            console.warn(e.detailMessage);
            this.errors = e.data.errors;
        }

        if (response) {
            this.onHide();
            this.$root.$emit('onAddVideo', {
                id: response.id,
                link: this.form.link,
            });
            this.$notify('Видео успешно добавлено.');
            this.$root.$auth.videosIncrease();
        }
        else {
            this.isStoreRequest = false;
        }
    },
},

mounted() {
    setTimeout(() => {
        this.$refs.videoLink.focus();
    }, 100);
},
}
</script>
