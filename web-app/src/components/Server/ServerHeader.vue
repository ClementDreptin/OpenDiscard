<template>
    <header class="server-header">
        <h6 class="title is-6">{{ $store.state.currentServer.name }}</h6>
        <ServerSettings v-if="$store.state.currentServer.owner_id === $store.state.user.id"
                        class="is-pulled-right server-settings-button"/>
    </header>
</template>

<script>
    import ServerSettings from "./ServerSettings";

    export default {
        name: "ServerHeader",
        components: {
            ServerSettings
        }
    }
</script>

<style scoped>
    .server-header {
        display: flex;
        padding-top: 0.5rem;
        border-bottom: solid 2px #272727;
    }

    .server-header > *:hover {
        background-color: transparent;
        color: #dcddde;
    }

    h6:hover + .server-settings-button {
        color: #a5a5a5;
    }

    h6 {
        flex: 7;
        color: #dcddde;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .server-settings-button {
        flex: 1;
        color: transparent;
        padding-left: 0;
        padding-right: 0;
    }
</style>