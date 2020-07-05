<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="textChannelName" hint="Text Channel name"/>
            <Button class="btn-success" text="Create" @tap="createTextChannel"/>
        </StackLayout>
    </Page>
</template>

<script>
    import errorHandler from "~/modules/Errors";

    export default {
        name: "CreateTextChannelModal",
        data() {
            return {
                textChannelName: ""
            }
        },
        methods: {
            createTextChannel() {
                if (this.textChannelName === "") {
                    return alert({
                        title: "Error",
                        message: "You must give your text channel a name!",
                        okButtonText: "OK"
                    });
                }

                global.axios.post(`servers/${this.$store.state.currentServer.id}/channels/`, {
                    name: this.textChannelName
                }).then(response => {
                    this.$store.state.currentServer.textChannels.push(response.data.text_channel);
                    global.bus.$emit('textChannelAdded');
                    this.$modal.close();
                }).catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
