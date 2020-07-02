<template>
    <StackLayout orientation="horizontal" @tap="serverClick">
        <Image v-if="server.image_url"
               :src="`${axios.defaults.baseURL}${server.image_url}`"
               class="server-image"
               :class="{active: isActive}"/>
        <StackLayout v-else
               class="server-icon"
               :class="{active: isActive}"
               textWrap="true">
            <Label :text="getServerIconText()" class="server-icon-text"/>
        </StackLayout>
    </StackLayout>
</template>

<script>
    export default {
        name: "Server",
        props: [
            'server'
        ],
        data() {
            return {
                axios: global.axios,
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
                global.bus.$emit('currentServerChanged');
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

<style scoped lang="scss">
    @import "../../color-variables.scss";

    @keyframes active-anim {
        from { background-color: $grey-2; }
        to { background-color: #7289da; }
    }

    StackLayout {
        margin-bottom: 8;
    }

    .server-image, .server-icon {
        width: 48;
        height: 48;
        border-radius: 100;
        background-color: $grey-2;
    }

    .server-icon {
        color: $white;

        &-text {
            padding-top: 11;
            font-size: 18;
            text-align: center;
        }

        &.active {
            animation-name: active-anim;
            animation-duration: .4s;
            animation-fill-mode: forwards;
            color: white;
        }
    }

    .active {
        border-radius: 10;
    }
</style>
