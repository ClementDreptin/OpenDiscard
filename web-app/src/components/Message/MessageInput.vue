<template>
    <input id="message-input" class="input" type="text" @keyup.enter="sendMessage()" v-model="messageContent" placeholder="Message">
</template>

<script>
    export default {
        name: "MessageInput",
        data() {
            return {
                messageContent: ""
            }
        },
        methods: {
            sendMessage() {
                if (this.messageContent === "") return;

                this.$socket.send(JSON.stringify({
                    action: 'message',
                    message: {
                        user: {
                            id: this.$store.state.user.id,
                            email: this.$store.state.user.email,
                            avatar_url: this.$store.state.user.avatar_url,
                            username: this.$store.state.user.username
                        },
                        content: this.messageContent
                    },
                    roomId: this.$store.state.currentTextChannel.id
                }));
            }
        }
    }
</script>

<style scoped>
    #message-input {
        width: 96%;
        padding: 0.5em 0.75em 0.5em 0.75em;
        margin: auto auto 0.7em;
    }
</style>