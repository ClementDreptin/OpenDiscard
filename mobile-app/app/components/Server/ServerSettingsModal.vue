<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="serverLocal.name" hint="Server name"/>
            <Button class="btn-success" text="Update" @tap="updateServer"/>
        </StackLayout>
    </Page>
</template>

<script>
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
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
