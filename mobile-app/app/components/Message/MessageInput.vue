<template>
    <FlexboxLayout>
        <TextField v-model="messageContent"
                   hint="Message"
                   autocorrect="true"
                   returnKeyType="send"
                   flexGrow="1"
                   @returnPress="sendMessage"/>
        <Button :isEnabled="messageContent.length > 0"
                @tap="sendMessage"
                class="fas">
            <FormattedString>
                <Span text.decode="&#xf1d8;" class="fas" fontSize="18"/>
            </FormattedString>
        </Button>
    </FlexboxLayout>
</template>

<script>
    import errorHandler from "~/modules/Errors";

    export default {
        name: "MessageInput",
        data() {
            return {
                messageContent: ""
            }
        },
        methods: {
            test() {
                console.log('test');
            },

            sendMessage() {
                if (this.messageContent === "") return;

                const params = { content: this.messageContent }

                global.axios.post(`/channels/${this.$store.state.currentTextChannel.id}/messages/`, params)
                    .then(response => {
                        const message = response.data.message;
                        const user = this.$store.state.user;

                        global.socket.send(JSON.stringify({
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

<style scoped lang="scss">
    @import "../../color-variables";

    TextField {
        color: $white;
        font-size: 15;
        placeholder-color: #A8A8A8;
        background-color: $grey-1;
        width: 96%;
        padding: 9 13.5;
        border-color: #A8A8A8;
        border-width: 1;
        border-radius: 4;
    }

    Button {
        :disabled {
            opacity: 0.5;
        }

        border-radius: 100%;
        background-color: #7289da;
        color: $white;
    }
</style>
