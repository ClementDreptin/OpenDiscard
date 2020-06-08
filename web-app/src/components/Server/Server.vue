<template>
    <figure class="image is-48x48">
        <a @click="serverClick">
            <img v-if="server.image"
                 class="is-rounded"
                 :src="`data:${server.image.mimetype};base64,${server.image.image}`"
                 :alt="`Image of the server ${server.name}`">
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
    a {
        padding: 0;
        border-radius: 10px;
    }

    a:hover {
        background-color: #c2c2c2;
    }

    figure {
        display: inline-block;
    }
</style>