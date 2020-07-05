<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="serverLocal.name" hint="Server name"/>
            <Button class="btn-danger" text="Delete this Server" textWrap="false" @tap="showDeleteModal"/>
            <Button class="btn-success" text="Update" @tap="updateServer"/>
        </StackLayout>
    </Page>
</template>

<script>
    import ConfirmDeleteModal from "~/components/General/ConfirmDeleteModal";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "ServerSettingsModal",
        data() {
            return {
                serverLocal: JSON.parse(JSON.stringify(this.$store.state.currentServer)), // Deep clone of the current server
                fileData: null, // For a possible future functionality where you'll be able to select an image from the device and make it the server image
            }
        },
        methods: {
            updateServer() {
                if (this.serverLocal.name === "") {
                    return alert({
                        title: "Error",
                        message: "You must give your server a name!",
                        okButtonText: "OK"
                    });
                }

                if (this.fileData) {
                    global.axios.post('/images/', {image: this.fileData})
                        .then(response => this.updateServerApi(response.data.url))
                        .catch(err => errorHandler(err, this))
                } else {
                    this.updateServerApi();
                }
            },

            updateServerApi(imageUrl) {
                let params = { name: this.serverLocal.name };
                if (imageUrl) params.image_url = imageUrl;

                global.axios.patch(`/servers/${this.$store.state.currentServer.id}`, params)
                    .then(response => {
                        this.$store.state.currentServer.name = response.data.server.name;
                        this.$store.state.currentServer.image_url = response.data.server.image_url;
                        this.$modal.close();
                    })
                    .catch(err => errorHandler(err, this));
            },

            showDeleteModal() {
                this.$showModal(ConfirmDeleteModal, {
                    props: {
                        element: 'Server',
                        actionTitle: 'Delete',
                        deleteFunction: this.deleteServer
                    }
                }).catch(err => console.log(err));
            },

            deleteServer() {
                global.axios.delete(`/servers/${this.serverLocal.id}`)
                    .then(response => {
                        let servers = this.$store.state.servers;
                        let currentServer = this.$store.state.currentServer;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = servers.findIndex(tc => tc.id === response.data.server.id);

                        servers.splice(index, 1);

                        if (currentServer.id === response.data.server.id) {
                            this.$store.state.currentServer = null;
                            global.bus.$emit('currentServerWasDeleted');
                            if (currentTextChannel && currentTextChannel.server_id === response.data.server.id) {
                                this.$store.state.currentTextChannel = null;
                                global.bus.$emit('currentTextChannelWasDeleted');
                            }
                        }

                        this.$modal.close();
                    })
                    .catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
