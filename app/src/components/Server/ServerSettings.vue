<template>
    <div>
        <div v-if="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="Server Options"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="serverLocal.name" placeholder="Name">
                            </p>
                        </div>
                        <InputFile/>
                        <ModalDeleteButton element="Server"/>
                        <ConfirmDeleteModal element="Server" actionTitle="Delete" :deleteFunction="deleteServer"/>
                        <ErrorBox/>
                    </section>
                    <ModalFooter :actionFunction="updateServer" actionTitle="Update"/>
                </form>
            </div>
        </div>
        <a @click="showModal = true;resetInputs()">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import ConfirmDeleteModal from "../General/ConfirmDeleteModal";
    import InputFile from "../General/InputFile";
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import ModalFooter from "../General/ModalFooter";
    import ModalDeleteButton from "../General/ModalDeleteButton";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "ServerSettings",
        components: {
            ConfirmDeleteModal,
            InputFile,
            ErrorBox,
            ModalHeader,
            ModalFooter,
            ModalDeleteButton
        },
        data() {
            return  {
                showModal: false,
                showConfirmDeleteModal: false,
                fail: null,
                serverLocal: JSON.parse(JSON.stringify(this.$store.state.currentServer)), // Deep clone of the current server
                fileName: "",
                fileData: null
            }
        },
        methods: {
            updateServer() {
                if (this.serverLocal.name === "") return this.fail = "You must give your server a name!";

                if (this.fileData) {
                    axios.post('/images/', {image: this.fileData})
                        .then(response => this.updateServerApi(response.data.url))
                        .catch(err => errorHandler(err, this))
                } else {
                    this.updateServerApi();
                }
            },

            updateServerApi(imageUrl) {
                let params = { name: this.serverLocal.name };
                if (imageUrl) params.image_url = imageUrl;

                axios.patch(`/servers/${this.$store.state.currentServer.id}`, params)
                    .then(response => {
                        this.$store.state.currentServer.name = response.data.server.name;
                        this.$store.state.currentServer.image_url = response.data.server.image_url;
                        this.showModal = false;
                    })
                    .catch(err => errorHandler(err, this));
            },

            deleteServer() {
                axios.delete(`/servers/${this.serverLocal.id}`)
                    .then(response => {
                        let servers = this.$store.state.servers;
                        let currentServer = this.$store.state.currentServer;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = servers.findIndex(tc => tc.id === response.data.server.id);

                        servers.splice(index, 1);

                        if (currentServer.id === response.data.server.id) {
                            this.$store.state.currentServer = null;
                            this.$bus.$emit('currentServerWasDeleted');
                            if (currentTextChannel && currentTextChannel.server_id === response.data.server.id) {
                                this.$store.state.currentTextChannel = null;
                                this.$bus.$emit('currentTextChannelWasDeleted');
                            }
                        }

                        this.showConfirmDeleteModal = false;
                        this.showModal = false;
                    })
                    .catch(err => errorHandler(err, this));
            },

            resetInputs() {
                this.serverLocal = JSON.parse(JSON.stringify(this.$store.state.currentServer));
                this.fileName = "";
                this.fileData = null;
                this.fail = null;
            }
        }
    }
</script>

<style scoped>
    a {
        color: inherit;
        text-align: right;
    }

    a:hover {
        color: white;
    }
</style>