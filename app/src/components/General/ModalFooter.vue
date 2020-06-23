<template>
    <footer class="modal-card-foot">
        <button @click="actionFunction" class="button is-success">{{ actionTitle }}</button>
        <button @click="$parent.showModal = false" class="button">Cancel</button>
    </footer>
</template>

<script>
    export default {
        name: "ModalFooter",
        props: [
            'actionFunction',
            'actionTitle'
        ]
    }
</script>

<style scoped>

</style>