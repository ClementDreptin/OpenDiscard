<template>
    <div id="home" class="columns" v-if="user">
        <div class="column">
            <ServersBar/>
        </div>
        <div v-show="$store.state.currentServer" class="column is-one-fifth">
            <TextChannels/>
        </div>
        <div v-show="$store.state.currentTextChannel" class="column is-half">
            <Messages/>
        </div>
        <div v-show="$store.state.currentServer" class="column is-one-fifth">
            <Members/>
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

</style>
