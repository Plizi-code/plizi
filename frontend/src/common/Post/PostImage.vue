<template>
    <img :src="src" :alt="alt" />
</template>

<script>
export default {
    name: 'PostImage',
    props: {
        src: {
            type: String,
            default: null,
        },
        alt: {
            type: String,
            default: null,
        },
    },
}
</script>
