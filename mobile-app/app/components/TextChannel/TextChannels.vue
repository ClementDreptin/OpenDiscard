<template>
    <StackLayout id="text-channels-container">
        <ServerHeader v-if="$store.state.currentServer"/>
        <ScrollView v-if="$store.state.currentServer">
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
                <TextChannel v-for="textChannel in textChannels" :textChannel="textChannel"/>
            </StackLayout>
        </ScrollView>
    </StackLayout>
</template>

<script>
    import TextChannel from "~/components/TextChannel/TextChannel";
    import ServerHeader from "~/components/Server/ServerHeader";
    import CreateTextChannelModal from "~/components/TextChannel/CreateTextChannelModal";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "TextChannels",
        components: {
            ServerHeader,
            TextChannel
        },
        data() {
            return {
                textChannels: []
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

            },

            leaveTextChannel() {

            },

            showCreateModal() {
                this.$showModal(CreateTextChannelModal).catch(err => console.log(err));
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
</style>
