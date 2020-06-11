<template>
    <aside class="menu">
        <ul class="menu-list">
            <li v-for="server in servers">
                <Server :server="server"/>
            </li>
            <li>
                <AddServerButton/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import Server from "./Server";
    import AddServerButton from "./AddServerButton";

    export default {
        name: "ServersBar",
        components: {
            Server,
            AddServerButton
        },
        data() {
            return {
                servers: []
            }
        },
        methods: {
            getServers() {
                axios.get('/servers/')
                    .then(response => {
                        this.$store.state.servers = response.data.servers;
                        this.servers = this.$store.state.servers;
                    })
                    .catch(err => console.log(err.response.data.message));
            },
        },
        mounted() {
            this.getServers();
        }
    }
</script>

<style scoped>
    li {
        text-align: center;
        margin-bottom: 0.4em;
    }

    aside {
        padding: 0.75em;
        margin-bottom: 2em;
    }
</style>