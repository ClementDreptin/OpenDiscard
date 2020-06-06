<template>
    <div id="home" class="columns" v-if="user">
        <div class="column">
            <ServersBar/>
        </div>
        <div v-show="$store.state.currentServer" class="column is-one-fifth">
            <TextChannels/>
        </div>
        <div v-show="$store.state.currentServer" class="column is-half">
            <div>Username: {{user.username}}</div>
            <div>E-mail address: {{user.email}}</div>
            <span>Profile picture: </span>
            <span v-if="user.avatar"><img :src="`data:${user.avatar.mimetype};base64,${user.avatar.image}`"></span>
            <span v-else>No profile picture</span>
        </div>
        <div v-show="$store.state.currentServer" class="column is-one-fifth">
            people in the server
        </div>
    </div>
</template>

<script>
    import ServersBar from "../components/Server/ServersBar";
    import TextChannels from "../components/TextChannel/TextChannels";

    export default {
        name: 'Home',
        components: {
            TextChannels,
            ServersBar
        },
        data() {
            return {

            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        methods: {

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
