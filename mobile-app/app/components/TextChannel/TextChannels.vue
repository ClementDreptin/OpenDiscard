<template>
    <StackLayout id="text-channels-container">
        <ServerHeader v-if="$store.state.currentServer"/>
        <ScrollView v-if="$store.state.currentServer" height="76%">
            <StackLayout id="text-channels">
                <FlexboxLayout class="text-channels-header" justifyContent="space-between">
                    <Label width="80%"
                           text="Text Channels"
                           class="text-channels-header-text"/>
                    <Image v-if="$store.state.currentServer.owner_id === $store.state.user.id"
                           @tap="showCreateModal"
                           src.decode="font://&#xf067;"
                           class="fas text-channels-header-button"
                           stretch="none"
                           fontSize="18"
                           opacity="0.5"/>
                </FlexboxLayout>
                <TextChannel v-for="textChannel in textChannels" :textChannel="textChannel" :key="textChannel.id"/>
            </StackLayout>
        </ScrollView>
        <FlexboxLayout id="user-info" alignItems="center" width="100%" justifyContent="space-around">
            <Image v-if="$store.state.user.avatar_url"
                   :src="`${axios.defaults.baseURL}${$store.state.user.avatar_url}`"
                   class="user-avatar"/>
            <Image v-else
                   src="~/assets/default-profile-picture.png"
                   class="user-avatar"/>
            <Label :text="$store.state.user.username" class="user-name" flexGrow="1"/>
            <Image @tap="showUserInfoModal"
                   src.decode="font://&#xf013;"
                   stretch="none"
                   color="#dcddde"
                   fontSize="18"
                   class="fas"
                   opacity="0.7"/>
        </FlexboxLayout>
    </StackLayout>
</template>

<script>
    import TextChannel from "~/components/TextChannel/TextChannel";
    import ServerHeader from "~/components/Server/ServerHeader";
    import CreateTextChannelModal from "~/components/TextChannel/CreateTextChannelModal";
    import UserInfoModal from "~/components/User/UserInfoModal";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "TextChannels",
        components: {
            ServerHeader,
            TextChannel
        },
        data() {
            return {
                textChannels: [],
                axios: global.axios
            }
        },
        methods: {
            getTextChannels() {
                global.axios.get(`/servers/${this.$store.state.currentServer.id}/channels/`)
                    .then(response => {
                        this.$store.state.currentServer.textChannels = response.data.text_channels;
                        this.textChannels = this.$store.state.currentServer.textChannels;
                        if (this.textChannels.length !== 0) {
                            this.$store.state.currentTextChannel = this.textChannels[0];
                            this.joinTextChannel();
                        } else {
                            this.leaveTextChannel();
                        }
                    }).catch(err => errorHandler(err, this));
            },

            joinTextChannel() {
                global.socket.send(JSON.stringify({
                    action: 'join',
                    roomId: this.$store.state.currentTextChannel.id
                }));
                global.bus.$emit('currentTextChannelChanged');
            },

            leaveTextChannel() {
                if (this.$store.state.currentTextChannel) {
                    global.socket.send(JSON.stringify({
                        action: 'leave',
                        roomId: this.$store.state.currentTextChannel.id
                    }));
                }
                this.$store.state.currentTextChannel = null;
                global.bus.$emit('currentTextChannelWasDeleted');
            },

            showCreateModal() {
                this.$showModal(CreateTextChannelModal).catch(err => console.log(err));
            },

            showUserInfoModal() {
                this.$showModal(UserInfoModal).catch(err => console.log(err));
            }
        },
        mounted() {
            global.bus.$on('currentServerChanged', () => {
                this.getTextChannels();
            });

            global.bus.$on('currentServerWasDeleted', () => {
                this.textChannels = [];
            });

            global.bus.$on('textChannelAdded', () => {
                this.getTextChannels();
            });
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    #text-channels {
        padding: 18;

        &-container {
            background-color: $grey-1;
        }
    }

    .text-channels-header {
        margin-bottom: 18;

        &-text {
            color: #7a7a7a;
            font-size: 13.5;
            letter-spacing: 0.2;
            text-transform: uppercase;
        }

        &-button {
            color: $white;
        }
    }

    #user-info {
        background-color: #292b2f;
        padding: 10;
    }

    .user-avatar {
        height: 48;
        width: 48;
    }

    .user-name {
        font-size: 16;
        color: $white;
        margin-left: 20;
    }
</style>
