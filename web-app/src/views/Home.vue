<template>
    <div id="home" class="columns" v-if="user">
        <div id="server-bar" class="column is-narrow">
            <ServersBar/>
        </div>
        <div class="columns column">
            <div id="text-channels" class="column is-2">
                <ServerHeader v-if="$store.state.currentServer"/>
                <TextChannels/>
                <UserInfo/>
            </div>
            <div id="messages" class="column is-8">
                <TextChannelHeader v-if="$store.state.currentTextChannel"/>
                <Messages/>
            </div>
            <div id="members" class="column is-2">
                <Members/>
            </div>
        </div>
    </div>
</template>

<script>
    import ServersBar from "../components/Server/ServersBar";
    import ServerHeader from "../components/Server/ServerHeader";
    import TextChannels from "../components/TextChannel/TextChannels";
    import TextChannelHeader from "../components/TextChannel/TextChannelHeader";
    import Members from "../components/Users/Members";
    import UserInfo from "../components/Users/UserInfo";
    import Messages from "../components/Message/Messages";

    export default {
        name: 'Home',
        components: {
            ServersBar,
            ServerHeader,
            TextChannels,
            TextChannelHeader,
            Members,
            UserInfo,
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
        width: 78px;
        padding: 0;
        scrollbar-width: none;
    }

    #text-channels {
        background-color: #2F3136;
        //position: relative;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    #text-channels > * {
        padding: 0.75rem 0.75rem 0;
    }

    #messages {
        background-color: #36393F;
        scrollbar-width: thin;
    }

    #members {
        background-color: #2F3136;
        scrollbar-width: thin;
    }

    .columns {
        display: flex;
        padding: 0;
        margin-top: 0;
        margin-left: 0;
        margin-right: 0;
    }

    .columns.column {
        margin-right: -6.001px;
    }

    .column.is-narrow {
        flex: none;
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

    #server-bar, #messages, #members {
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
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

    .input::placeholder {
        color: #dcddde;
        opacity: 0.4;
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

    .button.is-link {
        background-color: transparent;
        border: solid #3273dc 1px;
        color: #3273dc;
    }

    .button.is-link:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border: solid #5090f8 1px;
        color: #5090f8;
    }

    .input:focus {
        border: solid #5090f8 1px;
    }

    .file-label .file-cta {
        background-color: transparent;
        border: solid #3273dc 1px;
        color: #3273dc;
    }

    .file-label:hover .file-cta {
        background-color: rgba(0, 0, 0, 0.3);
        border: solid #5090f8 1px;
        color: #5090f8;
    }
</style>
