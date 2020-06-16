<template>
    <div>
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="Text Channel Options"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="textChannelLocal.name" placeholder="Name">
                            </p>
                        </div>
                        <button @click="showConfirmDeleteModal = true" class="button is-danger is-pulled-right">
                            Delete this Text Channel
                        </button>
                        <ConfirmDeleteModal element="Text Channel" :deleteFunction="deleteTextChannel"/>
                        <ErrorBox/>
                    </section>
                    <ModalFooter :actionFunction="updateTextChannel" actionTitle="Update"/>
                </form>
            </div>
        </div>
        <a @click="showModal = true;resetTextChannelName()">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import ConfirmDeleteModal from "../General/ConfirmDeleteModal";
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import ModalFooter from "../General/ModalFooter";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "TextChannelSettings",
        components: {
            ConfirmDeleteModal,
            ErrorBox,
            ModalHeader,
            ModalFooter
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
                if (this.textChannelLocal.name === "") return this.fail = "You must give the text channel a name!";

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
                    .catch(err => errorHandler(err, this));
            },

            deleteTextChannel() {
                axios.delete(`/channels/${this.textChannelLocal.id}`)
                    .then(response => {
                        let textChannels = this.$store.state.currentServer.textChannels;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = textChannels.findIndex(tc => tc.id === response.data.text_channel.id);

                        textChannels.splice(index, 1);

                        if (currentTextChannel.id === response.data.text_channel.id) {
                            this.$store.state.currentTextChannel = null;
                            this.$bus.$emit('currentTextChannelWasDeleted');
                        }

                        this.showConfirmDeleteModal = false;
                        this.showModal = false;
                    })
                    .catch(err => errorHandler(err, this));
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