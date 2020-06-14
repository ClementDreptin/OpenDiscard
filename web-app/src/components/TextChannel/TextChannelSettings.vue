<template>
    <div>
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Text Channel Options</p>
                        <button @click="showModal = false" class="delete"></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="textChannelLocal.name" placeholder="Name">
                            </p>
                        </div>
                        <button @click="showConfirmDeleteModal = true" class="button is-danger is-pulled-right">
                            Delete this text channel
                        </button>
                        <DeleteTextChannelModal/>
                        <div class="container">
                            <article class="message is-danger" v-show="fail">
                                <div class="message-header">
                                    <p>Error</p>
                                </div>
                                <div class="message-body">
                                    {{ fail }}
                                </div>
                            </article>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button @click="updateTextChannel" class="button is-success">Update</button>
                        <button @click="showModal = false" class="button">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
        <a @click="showModal = true;resetTextChannelName()">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import DeleteTextChannelModal from "./DeleteTextChannelModal";

    export default {
        name: "TextChannelSettings",
        components: {
            DeleteTextChannelModal
        },
        props: [
            'textChannel'
        ],
        data() {
            return {
                textChannelLocal: JSON.parse(JSON.stringify(this.textChannel)), // Deep clone of the prop
                showModal: false,
                showConfirmDeleteModal: false,
                fail: null
            }
        },
        methods: {
            resetTextChannelName() {
                this.textChannelLocal = JSON.parse(JSON.stringify(this.textChannel));
            },

            updateTextChannel() {
                const params = {
                    name: this.textChannelLocal.name
                }

                axios.patch(`/channels/${this.textChannelLocal.id}`, params)
                    .then(response => {
                        let textChannels = this.$store.state.currentServer.textChannels;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = textChannels.findIndex(tc => tc.id === response.data.text_channel.id);

                        textChannels[index].name = response.data.text_channel.name;

                        if (currentTextChannel.id === response.data.text_channel.id) {
                            currentTextChannel.name = response.data.text_channel.name;
                        }

                        this.showModal = false;
                    })
                    .catch(err => console.log(err.response.data.message));
            }
        }
    }
</script>

<style scoped>
    a {
        color: inherit;
        text-align: right;
        padding-left: 0;
        padding-right: 0;
    }

    a:hover {
        background-color: #2f3136;
        color: white;
    }
</style>