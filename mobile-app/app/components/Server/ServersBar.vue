<template>
    <ScrollView>
        <StackLayout id="server-bar">
            <Server v-for="server in servers" :server="server"/>
        </StackLayout>
    </ScrollView>
</template>

<script>
    import Server from "~/components/Server/Server";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "ServersBar",
        components: {
            Server
        },
        data() {
            return {
                servers: []
            };
        },
        methods: {
            getServers() {
                axios.get('/servers/')
                    .then(response => {
                        this.$store.state.servers = response.data.servers;
                        this.servers = this.$store.state.servers;
                    })
                    .catch(err => errorHandler(err, this));
            }
        },
        mounted() {
            this.getServers();
        }
    }
</script>

<style scoped lang="scss">
    #server-bar {
        padding: 12;
    }
</style>
