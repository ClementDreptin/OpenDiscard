<template>
    <FlexboxLayout justifyContent="space-between">
        <Label width="80%"
               @tap="textChannelClick"
               :text="textChannel.name"/>
        <Image v-if="$store.state.currentServer.owner_id === $store.state.user.id"
               @tap="showSettingsModal"
               src.decode="font://&#xf013;"
               stretch="none"
               fontSize="18"
               class="fas text-channel-settings-button"
               opacity="0.5"/>
    </FlexboxLayout>
</template>

<script>
    import TextChannelSettingsModal from "~/components/TextChannel/TextChannelSettingsModal";

    export default {
        name: "TextChannel",
        props: [
            'textChannel'
        ],
        methods: {
            showSettingsModal() {
                this.$showModal(TextChannelSettingsModal, {
                    props: {
                        textChannel: this.textChannel
                    }
                }).catch(err => console.log(err));
            },

            textChannelClick() {
               /* if (this.$store.state.currentTextChannel) {
                    global.socket.send(JSON.stringify({
                        action: 'leave',
                        roomId: this.$store.state.currentTextChannel.id
                    }));
                }*/

                this.$store.state.currentTextChannel = this.textChannel;

                /*global.socket.send(JSON.stringify({
                    action: 'join',
                    roomId: this.$store.state.currentTextChannel.id
                }));*/

                global.bus.$emit('currentTextChannelChanged');
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables.scss";

    Label {
        font-size: 18;
        color: $white;
    }

    FlexboxLayout {
        margin-bottom: 12;
    }

    .text-channel-settings-button {
        color: $white;
    }
</style>
