<template>
    <div class="user-to-add">
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
    .user-to-add {
        display: flex;
        padding: 0.25em;
    }

    .user-to-add figure {
        margin-right: 1em;
    }

    .user-to-add div {
        flex: 10;
        padding: 0.5em;
    }

    .user-to-add button {
        flex: 2;
    }
</style>