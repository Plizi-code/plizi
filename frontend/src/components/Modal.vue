<template>
    <div class="modal fade"
         :class="{show: clickable && isVisible, 'modal-show': clickable && isVisible}"
         :id="modalId"
         tabindex="-1"
         role="dialog"
         :aria-labelledby="modalId"
         aria-hidden="true"
         @click.stop="clickable && isVisible ? $emit('hideModal') : null">
        <div class="modal-dialog"
             :class="{'modal-dialog-centered': vertically, 'modal-xl': size === 'xl'}"
             @click.stop=""
             role="document">
            <div class="modal-content">
                <slot name="header"></slot>
                <div class="modal-body">
                    <slot></slot>
                </div>

                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "Modal",
    props: {
      modalId: {
        type: String,
        default: null,
        required: true,
      },
      vertically: {
        type: Boolean,
        default: false,
      },
      clickable: {
        type: Boolean,
        default: false,
      },
      isVisible: {
        type: Boolean,
        default: false,
      },
      size: {
        type: String,
        default: '',
      },
    },
  }
</script>

<style lang="scss">
    .modal-show {
        display: block;
        background-color: rgba(0, 0, 0, .7);
    }
</style>
