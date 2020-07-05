<template>
    <StackLayout>
        <FlexboxLayout justifyContent="space-between">
            <Label width="80%"
                   :text="$store.state.currentServer.name"/>
            <Image v-show="$store.state.currentServer.owner_id === $store.state.user.id"
                   @tap="showSettingsModal"
                   src.decode="font://&#xf013;"
                   stretch="none"
                   color="#dcddde"
                   fontSize="18"
                   class="fas"
                   opacity="0.7"/>
            <Image v-show="$store.state.currentServer.owner_id !== $store.state.user.id"
                   @tap="showLeaveModal"
                   src.decode="font://&#xf0a8;"
                   stretch="none"
                   color="rgb(201,33,66)"
                   fontSize="18"
                   class="fas"
                   opacity="0.7"/>
        </FlexboxLayout>
        <StackLayout class="hr"/>
    </StackLayout>
</template>

<script>
    import ServerSettingsModal from "~/components/Server/ServerSettingsModal";
    import ConfirmDeleteModal from "~/components/General/ConfirmDeleteModal";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "ServerHeader",
        methods: {
            showSettingsModal() {
                this.$showModal(ServerSettingsModal).catch(err => console.log(err));
            },

            showLeaveModal() {
                this.$showModal(ConfirmDeleteModal, {
                    props: {
                        element: 'Server',
                        actionTitle: 'Leave',
                        deleteFunction: this.leaveServer
                    }
                }).catch(err => console.log(err));
            },

            leaveServer() {
                global.axios.delete(`/servers/${this.$store.state.currentServer.id}/users/${this.$store.state.user.id}`)
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
                    }).catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped lang="scss">
    Label {
        font-size: 18;
        color: white;
    }

    FlexboxLayout {
        padding: 18;
    }
</style>
