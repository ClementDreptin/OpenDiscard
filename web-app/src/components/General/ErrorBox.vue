<template>
    <div class="container">
        <article class="message is-danger" v-show="$parent.fail">
            <div class="message-header">
                <p>Error</p>
            </div>
            <div class="message-body">
                {{ $parent.fail }}
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        name: "ErrorBox"
    }
</script>

<style scoped>

</style>