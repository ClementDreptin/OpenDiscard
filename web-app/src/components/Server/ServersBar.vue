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
        data() {
            return {
                servers: []
            }
        },
        methods: {
            getServers() {
                axios.get('/servers/?image=true')
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

</style>