<template>
    <div class="field">
        <button @click="showConfirmDeleteModal = true" class="button is-danger">
            Delete this {{ element }}
        </button>
    </div>
</template>

<script>
    export default {
        name: "ModalDeleteButton",
        props: [
            'element'
        ]
    }
</script>

<style scoped>
    div {
        text-align: right;
    }
</style>