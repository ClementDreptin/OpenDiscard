<template>
    <div>
        <input id="message-input" class="input" type="text" @keyup.enter="sendMessage()" v-model="messageContent" placeholder="Message">
        <ErrorModal/>
    </div>
</template>

<script>
    import ErrorModal from "../General/ErrorModal";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "MessageInput",
        components: {
            ErrorModal
        },
        data() {
            return {
                messageContent: "",
                fail: null
            }
        },
        methods: {
            sendMessage() {
                if (this.messageContent === "") return;

                const params = { content: this.messageContent }

                axios.post(`/channels/${this.$store.state.currentTextChannel.id}/messages/`, params)
                    .then(response => {
                        const message = response.data.message;
                        const user = this.$store.state.user;

                        this.$socket.send(JSON.stringify({
                            action: 'message',
                            message: {
                                id: message.id,
                                content: message.content,
                                created_at: message.created_at,
                                updated_at: message.updated_at,
                                user_id: message.user_id,
                                channel_id: message.channel_id,
                                author: {
                                    id: user.id,
                                    username: user.username,
                                    email: user.email,
                                    avatar_url: user.avatar_url
                                }
                            },
                            roomId: message.channel_id
                        }));

                        this.messageContent = "";
                    }).catch(err => errorHandler(err, this));
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