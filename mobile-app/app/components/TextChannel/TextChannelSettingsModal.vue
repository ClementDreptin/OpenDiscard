<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="textChannelLocal.name" hint="Text Channel name"/>
            <Button class="btn-danger" text="Delete this Text Channel" textWrap="false" @tap="showDeleteModal"/>
            <Button class="btn-success" text="Update" @tap="updateTextChannel"/>
        </StackLayout>
    </Page>
</template>

<script>
    import ConfirmDeleteModal from "~/components/General/ConfirmDeleteModal";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "TextChannelSettingsModal",
        props: [
            'textChannel'
        ],
        data() {
            return {
                textChannelLocal: JSON.parse(JSON.stringify(this.textChannel)), // Deep clone of the prop
            }
        },
        methods: {
            updateTextChannel() {
                if (this.textChannelLocal.name === "") {
                    return alert({
                        title: "Error",
                        message: "You must give the text channel a name!",
                        okButtonText: "OK"
                    });
                }

                const params = {
                    name: this.textChannelLocal.name
                }

                global.axios.patch(`/channels/${this.textChannelLocal.id}`, params)
                    .then(response => {
                        let textChannels = this.$store.state.currentServer.textChannels;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = textChannels.findIndex(tc => tc.id === response.data.text_channel.id);

                        textChannels[index].name = response.data.text_channel.name;

                        if (currentTextChannel.id === response.data.text_channel.id) {
                            currentTextChannel.name = response.data.text_channel.name;
                        }

                        this.$modal.close();
                    })
                    .catch(err => errorHandler(err, this));
            },

            showDeleteModal() {
                this.$showModal(ConfirmDeleteModal, {
                    props: {
                        element: 'Text Channel',
                        actionTitle: 'Delete',
                        deleteFunction: this.deleteTextChannel
                    }
                }).catch(err => console.log(err));
            },

            deleteTextChannel() {
                global.axios.delete(`/channels/${this.textChannelLocal.id}`)
                    .then(response => {
                        let textChannels = this.$store.state.currentServer.textChannels;
                        let currentTextChannel = this.$store.state.currentTextChannel;
                        let index = textChannels.findIndex(tc => tc.id === response.data.text_channel.id);

                        textChannels.splice(index, 1);

                        if (currentTextChannel.id === response.data.text_channel.id) {
                            this.$store.state.currentTextChannel = null;
                            global.bus.$emit('currentTextChannelWasDeleted');
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
