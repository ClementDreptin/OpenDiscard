<template>
    <aside id="server-bar" class="menu">
        <ul class="menu-list">
            <li v-for="server in servers">
                <Server :server="server"/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import Server from "./Server";

    export default {
        name: "ServersBar",
        components: {
            Server
        },
        computed: {
            servers() {
                return this.$store.state.servers;
            }
        }
    }
</script>

<style scoped>

</style>