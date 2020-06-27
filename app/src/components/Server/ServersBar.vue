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
        <ErrorModal/>
    </aside>
</template>

<script>
    import Server from "./Server";
    import AddServerButton from "./AddServerButton";
    import ErrorModal from "../General/ErrorModal";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "ServersBar",
        components: {
            Server,
            AddServerButton,
            ErrorModal
        },
        data() {
            return {
                servers: [],
                fail: null
            }
        },
        methods: {
            getServers() {
                axios.get('/servers/')
                    .then(response => {
                        this.$store.state.servers = response.data.servers;
                        this.servers = this.$store.state.servers;
                    })
                    .catch(err => errorHandler(err, this));
            },
        },
        mounted() {
            this.getServers();

            this.$bus.$on('serverAdded', () => {
                this.servers = this.$store.state.servers;
            });
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