<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="serverName" hint="Server name"/>
            <Button class="btn-success" text="Create" @tap="createServer"/>
        </StackLayout>
    </Page>
</template>

<script>
    import errorHandler from "~/modules/Errors";

    export default {
        name: "CreateServerModal",
        data() {
            return {
                serverName: "",
                fileData: null, // For a possible future functionality where you'll be able to select an image from the device and make it the server image
            }
        },
        methods: {
            createServer() {
                if (this.serverName === "") {
                    return alert({
                        title: "Error",
                        message: "You must give your server a name!",
                        okButtonText: "OK"
                    });
                }

                if (this.fileData) {
                    axios.post('/images/', {image: this.fileData})
                        .then(response => this.createServerApi(response.data.url))
                        .catch(err => errorHandler(err, this))
                } else {
                    this.createServerApi();
                }
            },

            createServerApi(imageUrl) {
                let params = { name: this.serverName };
                if (imageUrl) params.image_url = imageUrl;
                axios.post('/servers/', params)
                    .then(response => {
                        this.$store.state.servers.push(response.data.server);
                        global.bus.$emit('serverAdded');
                        this.$modal.close();
                    })
                    .catch(err => errorHandler(err, this));
            },
        }
    }
</script>

<style scoped lang="scss">

</style>
