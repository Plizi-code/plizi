<template>
    <div class="modal show" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true"
         style="display: block; background-color: rgba(0, 0, 0, .7);" @click.stop="hideRegistrationModal">
        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <!--                <div class="modal-header">-->
                <!--                    <h4 class="modal-title">Быстрая регистрация</h4>-->
                <!--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideAlertModal">-->
                <!--                        <span aria-hidden="true">&times;</span>-->
                <!--                    </button>-->
                <!--                </div>-->

                <div class="modal-body">
                    <div v-if="isSuccessRegistration"
                         class="successRegistration">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h3 class="mb-3">Вы успешно зарегистрированы</h3>
                                <div class="mb-3">
                                    <i class="far fa-thumbs-up"></i>
                                </div>
                                <p class="mb-4">
                                    Мы выслали Вам письмо на Ваш <b style="color: blue">{{ user.email }}</b>.<br>
                                    Для завершения регистрации Вам нужно подтвердить почтовый адрес.
                                </p>
                                <button class="btn plz-btn plz-btn-primary"
                                        @click="hideRegistrationModal">
                                    Закрыть
                                </button>
                            </div>
                        </div>
                    </div>
                    <RegistrationForm v-else
                                      @successRegistration="successRegistration"></RegistrationForm>
                </div>

                <!--                <div class="modal-footer">-->
                <!--                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="hideAlertModal">Close</button>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
    import RegistrationForm from './RegistrationForm.vue';


    export default {
        name: 'RegistrationModal',
        props: {
            regModalVisible: Boolean,
        },
        components: {
            RegistrationForm,
        },
        data() {
            return {
                isSuccessRegistration: false,
            }
        },
        methods: {
            fillUserCredentialsInLoginForm(user) {
                this.$emit('successRegistration', user);
            },
            hideRegistrationModal() {
                this.$root.$emit('hideRegistrationModal', {});

                if (this.user) {
                    this.fillUserCredentialsInLoginForm(this.user);
                }
            },
            successRegistration(user) {
                this.isSuccessRegistration = true;
                this.user = user;

                setTimeout(() => {
                    this.hideRegistrationModal();
                }, 60000);
            },
        }
    }
</script>

<style lang="scss">
    .successRegistration {
        i {
            font-size: 3rem;
            color: #3D51DE;
        }
    }
</style>
