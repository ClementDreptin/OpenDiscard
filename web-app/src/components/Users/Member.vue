<template>
    <div>
        <span>
            <figure class="image is-32x32">
                <img v-if="member.avatar_url" class="is-rounded"
                     :src="`${axios.defaults.baseURL}${member.avatar_url}`"
                     :alt="`${member.username}'s profile picture`">
                <img v-else
                     src="../../assets/default-profile-picture.svg"
                     :alt="`${member.username}'s profile picture`">
            </figure>
        </span>
        <span class="member-name">{{ member.username }}</span>
        <a v-if="$store.state.currentServer.owner_id === $store.state.user.id" class="kick-button">
            <span>X</span>
        </a>
    </div>
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
    div {
        display: flex;
        padding: 0.4em 0;
    }

    span {
        color: #a5a5a5;
        margin-top: 0.4em;
        margin-bottom: 0.4em;
        margin-right: 0.4em;
    }

    div:hover > span, .kick-button:hover > span {
        color: white;
    }

    .member-name {
        flex-grow: 1;
        margin-top: 0.6em;
        white-space: nowrap;
        text-overflow: ellipsis;
        min-width: 5em;
        overflow: hidden;
    }

    .kick-button {
        text-align: right;
        padding-right: 0;
    }

    .kick-button:hover {
        background-color: transparent;
    }

    .kick-button > span {
        margin: 0;
    }
</style>