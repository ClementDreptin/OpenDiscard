<template>
    <div v-if="$parent.fail" class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <form @submit.prevent="">
                <ModalHeader title="Error"/>
                <section class="modal-card-body">
                    {{ $parent.fail }}
                </section>
                <footer class="modal-card-foot">
                    <button @click="$parent.fail = null" class="button is-danger">Ok</button>
                </footer>
            </form>
        </div>
    </div>
</template>

<script>
    import ModalHeader from "./ModalHeader";

    export default {
        name: "ErrorModal",
        components: {
            ModalHeader
        }
    }
</script>

<style scoped>

</style>