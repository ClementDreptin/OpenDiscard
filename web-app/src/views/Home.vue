<template>
    <div id="home" v-if="user">
        <div id="server-bar">
            <ServersBar/>
        </div>
        <div class="columns">
            <div class="column is-one-fifth">
                <TextChannels/>
            </div>
            <div class="column is-three-fifths">
                <Messages/>
            </div>
            <div class="column is-one-fifth">
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

<style scoped>
    #home {
        height: 100%;
        display: flex;
    }

    #server-bar {
        background-color: #393939;
        flex: 1;
    }

    .columns {
        margin-top: 0;
        flex: 20;
    }
</style>
