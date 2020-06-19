<template>
    <div>
        <div v-if="showModal" class="modal is-active">
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
                    <ModalFooter :actionFunction="createTextChannel" actionTitle="Create"/>
                </form>
            </div>
        </div>
        <div class="add-text-channel-button">
            <span class="menu-label">Text Channels</span>
            <a v-if="$store.state.currentServer.owner_id === $store.state.user.id"
               @click="showModal = true;textChannelName = ''">
                <span class="plus-sign"><i class="fa fa-plus"></i></span>
            </a>
        </div>
    </div>
</template>

<script>
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import ModalFooter from "../General/ModalFooter";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "AddTextChannelButton",
        components: {
            ErrorBox,
            ModalHeader,
            ModalFooter
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
    .plus-sign {
        font-size: 0.6em;
    }

    .menu-list a:hover {
        background-color: transparent;
        color: #a5a5a5;
    }

    .add-text-channel-button {
        display: flex;
    }

    .add-text-channel-button > span {
        flex: 5;
        padding-top: 0.7em;
    }

    .add-text-channel-button > a {
        text-align: right;
        flex: 1;
        color: #a5a5a5;
        font-size: 1.3rem;
        padding: 0;
    }

    .add-text-channel-button > a:hover {
        color: white;
    }
</style>