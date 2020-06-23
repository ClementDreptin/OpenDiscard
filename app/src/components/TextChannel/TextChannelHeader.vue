<template>
    <header>
        <h6 class="title is-6">{{ $store.state.currentTextChannel.name }}</h6>
    </header>
</template>

<script>
    export default {
        name: "TextChannelHeader"
    }
</script>

<style scoped>
    h6 {
        color: #a5a5a5;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        padding-bottom: 1.5rem;
        padding-top: 0.5rem;
        border-bottom: solid 2px #272727;
    }
</style>