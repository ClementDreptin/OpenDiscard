<template>
    <div class="user-to-invite">
        <figure class="image is-32x32">
            <img class="is-rounded"
                 :src="`${axios.defaults.baseURL}${user.avatar_url}`"
                 :alt="`${user.username}'s profile picture`">
        </figure>
        <div>{{ user.username }}</div>
        <button @click="addUser" class="button is-success">Add</button>
    </div>
</template>

<script>
    export default {
        name: "UserToAdd",
        props: [
            'user'
        ],
        data() {
            return {
                axios: axios
            }
        },
        methods: {
            addUser() {

            }
        }
    }
</script>

<style scoped>
    .user-to-invite {
        display: flex;
        padding: 0.5em;
    }

    .user-to-invite figure {
        flex: 1;
        margin-right: 1em;
    }

    .user-to-invite div {
        flex: 10;
        padding: 0.5em;
    }

    .user-to-invite button {
        flex: 2;
    }
</style>