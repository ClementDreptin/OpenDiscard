<template>
    <ScrollView>
        <StackLayout id="server-bar">
            <Server v-for="server in servers" :server="server"/>
            <StackLayout class="plus-sign" @tap="showCreateModal">
                <Label text="+"/>
            </StackLayout>
        </StackLayout>
    </ScrollView>
</template>

<script>
    import Server from "~/components/Server/Server";
    import CreateServerModal from "~/components/Server/CreateServerModal";
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
            },

            showCreateModal() {
                this.$showModal(CreateServerModal).catch(err => console.log(err));
            }
        },
        mounted() {
            this.getServers();

            global.bus.$on('serverAdded', () => {
                this.servers = this.$store.state.servers;
            });
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    #server-bar {
        padding: 12;
        background-color: $grey-0;
    }

    .plus-sign {
        width: 48;
        height: 48;
        border-radius: 100;
        background-color: $grey-2;

        Label {
            color: $success-hover;
            padding-top: -5;
            font-size: 40;
            font-weight: 200;
            text-align: center;
        }
    }
</style>
