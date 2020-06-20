<template>
    <aside class="menu" v-if="$store.state.currentServer">
        <ul class="menu-list">
            <li>
                <AddTextChannelButton/>
            </li>
            <li v-for="textChannel in textChannels">
                <TextChannel :textChannel="textChannel"/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import TextChannel from "./TextChannel";
    import AddTextChannelButton from "./AddTextChannelButton";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "TextChannels",
        components: {
            TextChannel,
            AddTextChannelButton
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
                this.$socket.send(JSON.stringify({
                    action: 'join',
                    roomId: this.$store.state.currentTextChannel.id
                }));
                this.$bus.$emit('currentTextChannelChanged');
            },

            leaveTextChannel() {
                if (this.$store.state.currentTextChannel) {
                    this.$socket.send(JSON.stringify({
                        action: 'leave',
                        roomId: this.$store.state.currentTextChannel.id
                    }));
                }
                this.$store.state.currentTextChannel = null;
                this.$bus.$emit('currentTextChannelWasDeleted');
            }
        },
        mounted() {
            this.$bus.$on('currentServerChanged', () => {
                this.getTextChannels();
            });

            this.$bus.$on('currentServerWasDeleted', () => {
                this.textChannels = [];
            });

            this.$bus.$on('textChannelAdded', () => {
                this.getTextChannels();
            });
        }
    }
</script>

<style scoped>
    aside {
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
        scrollbar-width: thin;
        height: 100%;
    }

    ul {
        padding-bottom: 0.5em;
    }
</style>