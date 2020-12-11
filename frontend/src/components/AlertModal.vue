<template>
    <div class="modal show" tabindex="-1" role="dialog" aria-labelledby="Modal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);" @click.stop="hideAlertModal">

        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" @click.stop="">
            <div class="bg-white-br20 modal-content" :class="(alertClass || '')">

                <div class="modal-body">
                    <div class="modal-inner-body p-5" v-html="alertMessage"></div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
name : 'AlertModal',
props: {
    alertMessage : String,
    alertClass : String,
    alertTimeout : Number
},

methods : {
    hideAlertModal(){
        this.$root.$emit('hideAlertModal', {});
    }
},

mounted(){
    if (this.alertTimeout > 0) {
        window.setTimeout(()=>{
            this.$root.$emit('hideAlertModal', {});
        }, this.alertTimeout*1000);
    }
}

}
</script>
