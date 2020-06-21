<template>
    <div class="messages-container">
        <div class="messages-custom">
            <Message v-for="message in messages" :message="message"/>
        </div>
    </div>
</template>

<script>
    import Message from "./Message";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "Messages",
        components: {
            Message
        },
        data() {
            return {
                messages: []
            }
        },
        methods: {
            getMessages(page = 1, size = 10, order = 'desc', authors = true) {
                axios.get(`/channels/${this.$store.state.currentTextChannel.id}/messages?page=${page}&size=${size}&order=${order}&authors=${authors}`)
                    .then(response => {
                        this.$store.state.currentTextChannel.messages = response.data.messages.reverse();
                        this.messages = this.$store.state.currentTextChannel.messages;
                    }).catch(err => errorHandler(err, this));
            }
        },
        mounted() {
            this.$bus.$on('currentTextChannelChanged', () => {
                this.getMessages();
            });

            this.$bus.$on('currentServerChanged', () => {
                this.messages = [];
            });

            this.$bus.$on('currentTextChannelWasDeleted', () => {
                this.messages = [];
            });

            this.$bus.$on('messageReceived', message => {
                this.messages.push(JSON.parse(message));
            });
        }
    }
</script>

<style scoped>
    .messages-container {
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
        scrollbar-width: thin;
    }

    .messages-custom {
        padding-bottom: 1.5em;
    }
</style>