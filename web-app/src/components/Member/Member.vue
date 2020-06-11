<template>
    <a>
        <span v-if="member.avatar_url">
            <figure class="image is-32x32">
                <img class="is-rounded"
                     :src="`${axios.defaults.baseURL}${member.avatar_url}`"
                     :alt="`${member.username}'s profile picture`">
            </figure>
        </span>
        <span class="member-name">{{ member.username }}</span>
    </a>
</template>

<script>
    export default {
        name: "Member",
        data() {
            return {
                axios: axios
            }
        },
        props: [
            'member'
        ]
    }
</script>

<style scoped>
    a {
        display: flex;
        padding: 0.4em 0;
    }

    span {
        color: #a5a5a5;
        margin-top: 0.4em;
        margin-bottom: 0.4em;
        margin-right: 0.4em;
    }

    a:hover {
        background-color: #2F3136;
    }

    a:hover > span {
        color: white;
    }

    .member-name {
        margin-top: 0.6em;
        white-space: nowrap;
        text-overflow: ellipsis;
        min-width: 5em;
        overflow: hidden;
    }
</style>