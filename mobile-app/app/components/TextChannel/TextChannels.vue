<template>
    <StackLayout id="text-channels-container">
        <ServerHeader v-if="$store.state.currentServer"/>
        <ScrollView v-if="$store.state.currentServer">
            <StackLayout id="text-channels">
                <TextChannel v-for="textChannel in textChannels" :textChannel="textChannel"/>
            </StackLayout>
        </ScrollView>
    </StackLayout>
</template>

<script>
    import TextChannel from "~/components/TextChannel/TextChannel";
    import errorHandler from "~/modules/Errors";
    import ServerHeader from "~/components/Server/ServerHeader";

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
                axios.get(`/servers/${this.$store.state.currentServer.id}/channels/`)
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
</style>
