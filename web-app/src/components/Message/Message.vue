<template>
    <div class="card">
        <div class="card-content">
            <div class="media">
                <div class="media-left" v-if="message.author.avatar">
                    <figure class="image is-48x48">
                        <img class="is-rounded" :src="`data:${message.author.avatar.mimetype};base64,${message.author.avatar.image}`">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">{{ message.author.username }}</p>
                </div>
            </div>

            <div class="content">
                {{ message.content }}
                <br>
                <time datetime="2016-1-1">{{ message.updated_at }}</time>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Message",
        props: [
            'message'
        ]
    }
</script>

<style scoped>

</style>