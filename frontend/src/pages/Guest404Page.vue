<template>
    <div class="error-page d-flex">
        <Error404Message/>
     </div>
</template>

<script>
import Error404Message from "../common/Error404Message.vue";

export default {
name: "Guest404Page",
    components: {Error404Message},
}
</script>
