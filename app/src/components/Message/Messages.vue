<template>
    <div ref="messages" @scroll="onScroll" class="messages-container">
        <div class="messages-custom">
            <Message v-for="message in messages" :message="message"/>
        </div>
        <ErrorModal/>
    </div>
</template>

<script>
    import Message from "./Message";
    import ErrorModal from "../General/ErrorModal";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "Messages",
        components: {
            Message,
            ErrorModal
        },
        data() {
            return {
                messages: [],
                nbMessagesFetched: null,
                currentPage: 1,
                lastPage: null,
                fail: null
            }
        },
        methods: {
            getMessages(page = 1, size = 15, order = 'desc', authors = true) {
                axios.get(`/channels/${this.$store.state.currentTextChannel.id}/messages?page=${page}&size=${size}&order=${order}&authors=${authors}`)
                    .then(response => {
                        this.nbMessagesFetched = response.data.messages.length;

                        let aroundPage = response.data.links.last.href.split("page=");
                        let afterPage = aroundPage[aroundPage.length - 1];
                        this.lastPage = afterPage.split("&")[0];

                        if (this.messages.length === 0) {
                            this.$store.state.currentTextChannel.messages = response.data.messages.reverse();
                        } else {
                            this.$store.state.currentTextChannel.messages = response.data.messages.reverse().concat(this.$store.state.currentTextChannel.messages);
                        }
                        this.messages = this.$store.state.currentTextChannel.messages;
                        this.currentPage++;
                    }).catch(err => errorHandler(err, this));
            },

            onScroll() {
                if (this.$refs.messages.scrollTop === 0 && this.currentPage <= this.lastPage) {
                    this.getMessages(this.currentPage);
                }
            },

            resetData() {
                this.messages = [];
                this.nbMessagesFetched = null;
                this.currentPage = 1;
                this.lastPage = null;
            }
        },
        watch: {
            messages: function(newVal, oldVal) {
                this.$nextTick(() => {
                    if (oldVal.length === 0 && newVal.length !== 0) {
                        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                    } else if (this.nbMessagesFetched && this.$refs.messages.firstChild.childNodes[this.nbMessagesFetched]) {
                        this.$refs.messages.firstChild.childNodes[this.nbMessagesFetched].scrollIntoView();
                    }
                });
            }
        },
        mounted() {
            this.$bus.$on('currentTextChannelChanged', () => {
                this.resetData();
                this.getMessages();
            });

            this.$bus.$on('currentServerChanged', () => {
                this.resetData();
            });

            this.$bus.$on('currentTextChannelWasDeleted', () => {
                this.resetData();
            });

            this.$bus.$on('messageReceived', message => {
                this.messages.push(JSON.parse(message));
                this.$refs.messages.firstChild.lastChild.scrollIntoView();
            });
        }
    }
</script>

<style scoped>
    .messages-container {
        flex-grow: 1;
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
        scrollbar-width: thin;
    }

    .messages-custom {
        padding-bottom: 1.5em;
    }
</style>