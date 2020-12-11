<template>
    <div id="postInterest" class="col-12 col-xl-3 bg-white-br20 d-flex align-items-center mr-n3 py-2 py-xl-0">
        <div class="d-flex align-items-center">
            <IconFire class="mr-3"/>
            <p class="my-0">Сначала Интересные</p>
        </div>
        <div class="button-switch d-flex align-items-center justify-content-center ml-2 ml-lg-auto">
            <input type="checkbox" id="switch-blue" class="switch" :value="filter.interest" @click="interestSwitch"/>
            <label for="switch-blue" class="lbl-off">Off</label>
            <label for="switch-blue" class="lbl-on">On</label>
        </div>
    </div>
</template>
<script>
import IconFire from '../../icons/IconFire.vue';

export default {
    name : 'PostInterest',
    components : { IconFire },
    props: {
        filter: Object,
    },
    data() {
        return {
        }
    },
    methods: {
        interestSwitch() {
            this.$emit('interestSwitch', !this.filter.interest);
        }
    }
}
</script>
