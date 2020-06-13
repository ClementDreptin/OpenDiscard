<template>
    <figure class="image">
        <span v-show="isActive"></span>
        <a @click="serverClick">
            <img v-if="server.image_url"
                 :class="{active: isActive}"
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
                axios: axios,
                isActive: false
            }
        },
        methods: {
            serverClick() {
                this.$store.state.currentServer = this.server;
                this.$parent.$children.forEach(child => {
                    if (child.isActive === true) child.isActive = false;
                });
                this.isActive = !this.isActive;
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
        width: 48px;
        height: 48px;
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

    .active {
        border-radius: 10px;
    }

    span {
        position: absolute;
        display: block;
        width: 8px;
        height: 40px;
        border-radius: 0 4px 4px 0;
        margin-left: -15px;
        margin-top: 4px;
        background-color: #dcddde;
    }
</style>