<template lang="html">
    <Page>
        <ActionBar>
            <Label text="Members"></Label>
        </ActionBar>

        <GridLayout class="page__content">
            <Label class="page__content-icon fas" text.decode="&#xf002;"></Label>
            <Label class="page__content-placeholder" :text="message"></Label>
        </GridLayout>
    </Page>
</template>

<script>
    export default {
        data() {
            return {
                message: "<!-- Search page content goes here -->"
            };
        }
    }
</script>

<style lang="scss" scoped>

</style>
