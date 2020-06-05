<template>
    <div>
        <div v-if="user">
            <div>Username: {{user.username}}</div>
            <div>E-mail address: {{user.email}}</div>
            <span>Profile picture: </span>
            <span v-if="user.avatar"><img :src="`data:${user.avatar.mimetype};base64,${user.avatar.image}`"></span>
            <span v-else>No profile picture</span>
            <div>
                Servers:
                <ul>
                    <li v-for="server in servers">
                        <span>{{ server.name }}</span>
                        <img v-if="server.image" :src="`data:${server.image.mimetype};base64,${server.image.image}`">
                    </li>
                </ul>
            </div>
        </div>
        <button @click="debug">Debug button</button>
    </div>
</template>

<script>
    export default {
        name: 'Home',
        data() {
            return {
                serversReady: false
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },

            servers() {
                return this.$store.state.servers;
            }
        },
        methods: {
            debug() {
                console.log(this.servers);
            }
        },
        mounted() {
            if (!this.$store.state.user) {
                this.$router.push('/signIn');
            }
        }
    }
</script>
