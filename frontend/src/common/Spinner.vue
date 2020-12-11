<template>
    <div :class="spinnerClazz">
        <div class="text-center pr-3">
<!--            <i class="fas fa-spinner fa-3x fa-spin text-info"></i>-->
            <i class="fas fa-circle-notch fa-3x fa-spin spinner-color"></i>
        </div>
        <div class="pt-1 spinner-color text-center" v-if="!hideText">
            <h3 v-html="spinnerMessage"></h3>
        </div>
    </div>
</template>

<script>
export default {
    name : 'Spinner',
    props : {
        clazz : String,
        message: String,
        hideText: {
            type: Boolean,
            default: true,
        }
    },

    data(){
        return {
            defaultClazz: `col-12 py-5 px-5 d-flex flex-column align-items-center`,
            defaultMessage: `Данные загружаются`,
        }
    },

    computed: {
        spinnerClazz(){
            return this.clazz || this.defaultClazz;
        },
        spinnerMessage(){
            return this.message || this.defaultMessage;
        },
    }
}
</script>
