<template>
    <figure class="image">
        <span class="active-indicator" v-show="isActive"></span>
        <a @click="serverClick">
            <img v-if="server.image_url"
                 :class="{active: isActive}"
                 :src="`${axios.defaults.baseURL}${server.image_url}`"
                 :alt="`Image of the server ${server.name}`">

            <div v-else class="server-icon" :class="{active: isActive}">
                <div class="server-icon-text">
                    {{ getServerIconText() }}
                </div>
            </div>
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
            },

            getServerIconText() {
                let serverName = this.server.name;
                let iconText = serverName[0].toUpperCase();
                const separators = [' ', '-', '/', '\\', '_', '|', ',', ';', ':', '&'];

                for (let i = 0; i < serverName.length; i++) {
                    if (separators.includes(serverName[i]) && !separators.includes(serverName[i+1]) && serverName[i+1] !== undefined) {
                        iconText += serverName[i + 1].toUpperCase();
                    }
                }

                return iconText.substring(0, 4);
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

    .active-indicator {
        position: absolute;
        display: block;
        width: 8px;
        height: 40px;
        border-radius: 0 4px 4px 0;
        margin-left: -15px;
        margin-top: 4px;
        background-color: #dcddde;
    }

    .server-icon-text {
        padding-top: 14px;
    }

    .server-icon:hover {
        border-radius: 10px;
        background-color: #7289da;
        color: white;
    }

    .server-icon.active {
        background-color: #7289da;
        color: white;
    }

    .server-icon {
        color: #dcddde;
        border-radius: 100%;
        width: 48px;
        height: 48px;
        background-color: #36393F;
        -moz-transition: all .3s;
        -o-transition: all .3s;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    .active {
        border-radius: 10px;
    }
</style>