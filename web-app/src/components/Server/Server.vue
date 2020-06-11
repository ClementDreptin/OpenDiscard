<template>
    <figure class="image is-48x48">
        <a @click="serverClick">
            <img v-if="server.image_url"
                 :src="`${axios.defaults.baseURL}${server.image_url}`"
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
        data() {
            return {
                axios: axios
            }
        },
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
    }

    img:hover {
        border-radius: 10px;
    }

    .menu-list a:hover {
        background-color: transparent;
        color: transparent;
    }

    img {
        border-radius: 100%;
        background-color: #36393F;
        -moz-transition: all .3s;
        -o-transition: all .3s;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    figure {
        display: inline-block;
    }
</style>