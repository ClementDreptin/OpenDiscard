<template>
    <div v-if="user">
        <div>Username: {{user.username}}</div>
        <div>E-mail address: {{user.email}}</div>
        <div>Profile picture: </div>
        <div v-if="user.avatar"><img :src="`data:${user.avatar.mimetype};base64,${user.avatar.image}`"></div>
        <div v-else>No profile picture</div>
    </div>
</template>

<script>
    export default {
        name: 'Home',
        data() {
            return {
                user: this.$store.state.user
            }
        },
        mounted() {
            if (!this.$store.state.user) {
                this.$router.push('/signIn');
            }
        }
    }
</script>
