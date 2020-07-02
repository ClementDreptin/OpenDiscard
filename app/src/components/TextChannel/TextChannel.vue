<template>
    <div class="text-channel-container">
        <a @click="textChannelClick" class="text-channel-name">
            <span>{{ textChannel.name }}</span>
        </a>
        <TextChannelSettings v-if="$store.state.currentServer.owner_id === $store.state.user.id"
                             :textChannel="textChannel"
                             class="text-channel-options"/>
    </div>
</template>

<script>
    import TextChannelSettings from "./TextChannelSettings";

    export default {
        name: "TextChannel",
        components: {
            TextChannelSettings
        },
        props: [
            'textChannel'
        ],
        methods: {
            textChannelClick() {
                if (this.$store.state.currentTextChannel) {
                    this.$socket.send(JSON.stringify({
                        action: 'leave',
                        roomId: this.$store.state.currentTextChannel.id
                    }));
                }

                this.$store.state.currentTextChannel = this.textChannel;

                this.$socket.send(JSON.stringify({
                    action: 'join',
                    roomId: this.$store.state.currentTextChannel.id
                }));

                this.$bus.$emit('currentTextChannelChanged');
            }
        }
    }
</script>

<style scoped>
    .text-channel-container {
        display:flex;
    }

    .text-channel-options {
        color: transparent;
        flex: 1;
    }

    .text-channel-name {
        flex: 16;
        color: #a5a5a5;
        white-space: nowrap;
        text-overflow: ellipsis;
        min-width: 3em;
        display: block;
        overflow: hidden;
    }

    .text-channel-container *:hover {
        background-color: #2f3136;
        color: white;
    }

    .text-channel-name:hover + .text-channel-options {
        color: #a5a5a5;
    }
</style>