<template>
    <Page>
        <ActionBar>
            <Label text="Messages"></Label>
        </ActionBar>

        <StackLayout id="messages-container">
            <TextChannelHeader v-if="$store.state.currentTextChannel"/>
            <ActivityIndicator v-if="busy" color="#dcddde" :busy="busy"/>
            <ScrollView ref="messages" @scroll="onScroll" height="82%">
                <StackLayout id="messages">
                    <Message v-for="(message, index) in messages" :message="message" :ref="'msg' + index"/>
                </StackLayout>
            </ScrollView>
            <MessageInput v-if="$store.state.currentTextChannel"/>
        </StackLayout>
    </Page>
</template>

<script>
    import TextChannelHeader from "~/components/TextChannel/TextChannelHeader";
    import Message from "~/components/Message/Message";
    import MessageInput from "~/components/Message/MessageInput";
    import errorHandler from "~/modules/Errors";

    export default {
        components: {
            MessageInput,
            Message,
            TextChannelHeader
        },
        data() {
            return {
                messages: [],
                nbMessagesFetched: null,
                currentPage: 1,
                lastPage: null,
                busy: false
            }
        },
        methods: {
            getMessages(page = 1, size = 15, order = 'desc', authors = true) {
                this.busy = true;
                global.axios.get(`/channels/${this.$store.state.currentTextChannel.id}/messages?page=${page}&size=${size}&order=${order}&authors=${authors}`)
                    .then(response => {
                        this.busy = false;

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

            onScroll(args) {
                if (args.scrollY === 0 && this.currentPage <= this.lastPage) {
                    this.getMessages(this.currentPage);
                }
            },

            scrollToEnd() {
                setTimeout(() => {
                    const scrollView = this.$refs.messages.nativeView;
                    scrollView.scrollToVerticalOffset(scrollView.scrollableHeight, true);
                }, 100);
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
                        this.scrollToEnd();
                    } else if (this.nbMessagesFetched && this.$refs.messages.firstChild.childNodes[this.nbMessagesFetched - 1]) {
                        // Apparently refs are arrays with only one element instead of a simple
                        // object when they're created in a v-for. It makes no sense, I know...
                        const base = this.$refs.msg0[0].nativeView;
                        const target = this.$refs.messages.firstChild.childNodes[this.nbMessagesFetched - 1].nativeView;
                        const scrollView = this.$refs.messages.nativeView;
                        scrollView.scrollToVerticalOffset(target.getLocationRelativeTo(base).y);
                    }
                });
            }
        },
        mounted() {
            global.bus.$on('currentTextChannelChanged', () => {
                this.resetData();
                this.getMessages();
            });

            global.bus.$on('currentServerChanged', () => {
                this.resetData();
            });

            global.bus.$on('currentTextChannelWasDeleted', () => {
                this.resetData();
            });

            global.bus.$on('messageReceived', message => {
                this.messages.push(JSON.parse(message));
                this.$nextTick(() => {
                    this.scrollToEnd();
                });
            });
        }
    }
</script>

<style lang="scss" scoped>
    @import "../color-variables";

    #messages-container {
        background-color: $grey-2;
    }

    #messages {
        padding-bottom: 27;
    }
</style>
