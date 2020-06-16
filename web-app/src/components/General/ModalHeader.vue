<template>
    <header class="modal-card-head">
        <p class="modal-card-title">{{ title }}</p>
        <button @click="$parent.showModal = false" class="delete"></button>
    </header>
</template>

<script>
    export default {
        name: "ModalHeader",
        props: [
            'title'
        ]
    }
</script>

<style scoped>

</style>