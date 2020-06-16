<template>
    <div>
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="Create a Text Channel"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="textChannelName" placeholder="Name">
                            </p>
                        </div>
                        <ErrorBox/>
                    </section>
                    <footer class="modal-card-foot">
                        <button @click="createTextChannel" class="button is-success">Create</button>
                        <button @click="showModal = false" class="button">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
        <div class="add-text-channel-button">
            <a @click="showModal = true;textChannelName = ''">
                <span>+</span>
            </a>
        </div>
    </div>
</template>

<script>
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "AddTextChannelButton",
        components: {
            ErrorBox,
            ModalHeader
        },
        data() {
            return {
                showModal: false,
                textChannelName: "",
                fail: null
            }
        },
        methods: {
            createTextChannel() {
                if (this.textChannelName === "") return this.fail = "You must give the text channel a name!";

                axios.post(`servers/${this.$store.state.currentServer.id}/channels/`, {
                    name: this.textChannelName
                }).then(response => {
                    this.$store.state.currentServer.textChannels.push(response.data.text_channel);
                    this.$bus.$emit('textChannelAdded');
                    this.showModal = false;
                    this.textChannelName = "";
                }).catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped>
    .menu-list a:hover {
        background-color: transparent;
        color: #a5a5a5;
    }

    .add-text-channel-button {
        text-align: right;
    }

    .add-text-channel-button > a {
        display: inline;
        color: #a5a5a5;
        font-size: 1.3rem;
        padding: 0;
    }

    .add-text-channel-button > a:hover {
        color: white;
    }
</style>