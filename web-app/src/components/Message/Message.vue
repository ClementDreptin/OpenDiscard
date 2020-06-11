<template>
    <div class="message">
        <figure class="image is-48x48">
            <img class="is-rounded"
                 :src="`${axios.defaults.baseURL}${message.author.avatar_url}`"
                 :alt="`${message.author.username}'s profile picture`">
        </figure>
        <div class="message-content">
            <a class="author-name">
                <div>{{ message.author.username }}</div>
            </a>
            <div>
                {{ message.content }}
                <br>
                <time>{{ message.updated_at }}</time>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Message",
        data() {
            return {
                axios: axios
            }
        },
        props: [
            'message'
        ]
    }
</script>

<style scoped>
    .message {
        display: flex;
    }

    figure {
        margin: 0.5em;
    }

    .author-name {
        color: #ffffff;
        font-weight: bold;
        text-decoration: none !important;
    }

    .author-name:hover {
        text-decoration: underline !important;
    }

    .message-content {
        color: #dcddde;
        margin-left: 0.5em;
        margin-top: 0.5em;
    }

    .message-content > div {
        font-size: 1rem;
    }

    time {
        color: #72767d;
    }
</style>