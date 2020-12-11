<template>
    <div class="modal" id="chatVideoModal" tabindex="-1" role="dialog" aria-labelledby="chatVideoModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="onHide">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20 p-5">
                <h6 class="text-center mb-3">Вы уверены что хотите удалить видео?</h6>
                <div class="d-flex">
                    <button class="btn plz-btn plz-btn-primary p-0 ml-auto mr-2"
                            @click.stop="deleteVideo">
                        Да
                    </button>
                    <button class="btn plz-btn plz-btn-outline btn-registration p-0 mt-0 ml-2 mr-auto"
                            @click.stop="onHide">
                        Нет
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteVideoModal",
        props: {
            id: Number,
            isSuccess: Boolean,
        },
        methods: {
            onHide() {
                this.$emit('onHideDeleteVideoModal');
            },
            deleteVideo() {
                this.$emit('onSuccessDeleteVideoModal');
            },
        },
    }
</script>
