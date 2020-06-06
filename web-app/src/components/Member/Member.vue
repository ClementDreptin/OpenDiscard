<template>
    <span>{{ member.username }}</span>
</template>

<script>
    export default {
        name: "Member",
        props: [
            'member'
        ]
    }
</script>

<style scoped>

</style>