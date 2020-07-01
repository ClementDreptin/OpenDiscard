<template>
    <Page>
        <ActionBar>
            <Label text="Servers"></Label>
        </ActionBar>

        <ListView for="server in servers" @itemTap="serverClick">
            <v-template>
                <StackLayout orientation="horizontal">
                    <Label :text="server.name" textWrap="true"></Label>
                </StackLayout>
            </v-template>
        </ListView>
    </Page>
</template>

<script>
    import ServerDetails from "./ServerDetails";
    import errorHandler from "~/modules/Errors";

    export default {
        data() {
            return {
                servers: []
            };
        },
        methods: {
            serverClick(args) {
                this.$navigateTo(ServerDetails, {
                    frame: 'servers',
                    props: {server: args.item},
                    animated: true,
                    transition: {
                        name: "slide",
                        duration: 200,
                        curve: "ease"
                    }
                }).catch(err => console.log(err));
            },

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
    };
</script>

<style scoped lang="scss">

</style>
