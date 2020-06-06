<template>
    <span>{{ textChannel.name }}</span>
</template>

<script>
    export default {
        name: "TextChannel",
        props: [
            'textChannel'
        ]
    }
</script>

<style scoped>

</style>