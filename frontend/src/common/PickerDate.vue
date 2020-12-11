<template>
    <DatePicker v-model="date"
                @input="$emit('input', date)"
                :input-props="inputProps">
        <input slot-scope="{ inputProps, inputEvents, isDragging }"
               :class="clazz"
               v-bind="inputProps"
               v-on="inputEvents">
    </DatePicker>
</template>

<script>
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';

    export default {
        name: "PickerDate",
        components: {
            Calendar,
            DatePicker,
        },
        props: {
            value: Date|String,
            inputProps: Object,
            clazz: String,
        },
        data() {
            return {
                date: this.value,
            }
        },
    }
</script>

<style scoped>

</style>
