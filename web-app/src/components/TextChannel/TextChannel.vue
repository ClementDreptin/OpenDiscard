<template>
    <a>
        <span>{{ textChannel.name }}</span>
    </a>
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
    span {
        color: #a5a5a5;
        white-space: nowrap;
        text-overflow: ellipsis;
        min-width: 3em;
        display: block;
        overflow: hidden;
    }

    a:hover {
        background-color: #2F3136;
    }

    a:hover > span {
        color: white;
    }
</style>