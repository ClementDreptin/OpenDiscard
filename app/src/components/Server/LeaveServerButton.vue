<template>
    <div>
        <a @click="showConfirmDeleteModal = true">
            <span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
            </span>
        </a>
        <ConfirmDeleteModal element="Server" actionTitle="Leave" :deleteFunction="leaveServer"/>
        <ErrorModal/>
    </div>
</template>

<script>
    import ConfirmDeleteModal from "../General/ConfirmDeleteModal";
    import ErrorModal from "../General/ErrorModal";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "LeaveServerButton",
        components: {
            ConfirmDeleteModal,
            ErrorModal
        },
        data() {
            return {
                showConfirmDeleteModal: false,
                fail: null
            }
        },
        methods: {
            leaveServer() {
                axios.delete(`/servers/${this.$store.state.currentServer.id}/users/${this.$store.state.user.id}`)
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
                    }).catch(err => errorHandler(err, this));
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
        color: rgb(201,33,66);
    }
</style>