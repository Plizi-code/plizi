<template>
    <div class="modal" id="deletePhotoAlbumModal" tabindex="-1" role="dialog" aria-labelledby="deletePhotoAlbumModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="onHide">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20 p-5">
                <h6 class="text-center mb-3">Вы уверены что хотите удалить фотоальбом?</h6>
                <div class="d-flex">
                    <button class="btn plz-btn plz-btn-primary p-0 mr-3"
                            :disabled="isShipped"
                            @click.stop="onSuccessDelete">
                        Да
                    </button>
                    <button class="btn plz-btn plz-btn-primary p-0 mr-3"
                            :disabled="isShipped"
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
        name: "DeletePhotoAlbumModal",
        props: {
            id: Number,
            isShipped: Boolean,
        },
        methods: {
            onHide() {
                this.$emit('onHide');
            },
            onSuccessDelete() {
                this.$emit('onSuccessDelete');
            },
        },
    }
</script>

<style scoped>

</style>
