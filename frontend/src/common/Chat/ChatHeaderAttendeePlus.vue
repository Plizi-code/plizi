<template>
    <div class="mr-3 mt-3 cursor-pointer">
        <div class="media-pic border rounded-circle position-relative" @click.prevent="onPlusClick">
            <IconUserPlus class="w-50 h-50 " />
        </div>
    </div>
</template>

<script>
import IconUserPlus from '../../icons/IconUserPlus.vue';

export default {
name : 'ChatHeaderAttendeePlus',
components:{ IconUserPlus },

methods : {
    onPlusClick(){
        this.$emit('ShowAddAttendeeModal', {});
    }
}

}
</script>
