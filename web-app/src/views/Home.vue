<template>
    <div id="home" v-if="user">
        <div id="server-bar">
            <ServersBar/>
        </div>
        <div class="columns">
            <div id="text-channels" class="column is-one-fifth">
                <TextChannels/>
            </div>
            <div id="messages" class="column is-three-fifths">
                <Messages/>
            </div>
            <div id="members" class="column is-one-fifth">
                <Members/>
            </div>
        </div>
    </div>
</template>

<script>
    import ServersBar from "../components/Server/ServersBar";
    import TextChannels from "../components/TextChannel/TextChannels";
    import Members from "../components/Member/Members";
    import Messages from "../components/Message/Messages";

    export default {
        name: 'Home',
        components: {
            TextChannels,
            ServersBar,
            Members,
            Messages
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        mounted() {
            if (!this.$store.state.user) {
                this.$router.push('/signIn');
            }
        }
    }
</script>

<style>
    #home {
        height: 100%;
        display: flex;
    }

    #server-bar {
        background-color: #272727;
    }

    #text-channels {
        background-color: #2F3136;
    }

    #messages {
        background-color: #36393F;
    }

    #members {
        background-color: #2F3136;
    }

    .columns {
        margin-top: 0;
        margin-left: 0;
        margin-right: 0;
        flex: 20;
    }

    .columns > div, #server-bar {
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
        scrollbar-width: thin;
    }

    ::-webkit-scrollbar {
        width: 5px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(32,34,37,.6);
    }

    .modal-card {
        max-width: 80%;
    }

    .modal-card-head, .modal-card-foot {
        border: none;
        background-color: #2F3136;
    }

    .modal-card-body {
        color: #dcddde;
        background-color: #36393F;
    }

    .modal-card-title {
        color: #dcddde;
    }

    .input {
        background-color: #2F3136;
        color: #dcddde;
    }

    .button {
        background-color: transparent;
        border: solid #dcddde 1px;
        color: #dcddde;
    }

    .button:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border: solid white 1px;
        color: white;
    }

    .button.is-success {
        background-color: transparent;
        border: solid #3ec46d 1px;
        color: #3ec46d;
    }

    .button.is-success:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border: solid #52ea8c 1px;
        color: #52ea8c;
    }

    .button.is-danger {
        background-color: transparent;
        border: solid #f03a5f 1px;
        color: #f03a5f;
    }

    .button.is-danger:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border: solid #ff5877 1px;
        color: #ff5877;
    }
</style>
