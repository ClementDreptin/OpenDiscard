<template>
    <figure class="image is-48x48">
        <a @click="serverClick">
            <img class="is-rounded" v-if="server.image" :src="`data:${server.image.mimetype};base64,${server.image.image}`">
        </a>
    </figure>
</template>

<script>
    export default {
        name: "Server",
        props: [
            'server'
        ],
        methods: {
            serverClick() {
                this.$store.state.currentServer = this.server;
                this.$bus.$emit('currentServerChanged');
            }
        }
    }
</script>

<style scoped>

</style>