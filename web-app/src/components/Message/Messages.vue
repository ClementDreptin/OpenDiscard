<template>
    <div>
        <Message v-for="message in messages" :message="message"/>
    </div>
</template>

<script>
    import Message from "./Message";

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
                        this.$store.state.currentTextChannel.messages = response.data.messages;
                        this.messages = this.$store.state.currentTextChannel.messages;
                    }).catch(err => console.log(err.response.data.message));
            }
        },
        mounted() {
            this.$bus.$on('currentTextChannelChanged', () => {
                this.getMessages();
            });

            this.$bus.$on('currentServerChanged', () => {
                this.messages = [];
            });
        }
    }
</script>

<style scoped>
    div {
        background-color: #36393F;
        margin-bottom: 2em;
    }
</style>