<template lang="html">
    <Page>
        <ActionBar>
            <Label text="Messages"></Label>
        </ActionBar>

        <GridLayout class="page__content">
            <Label class="page__content-placeholder" :text="message"></Label>
        </GridLayout>
    </Page>
</template>

<script>
    export default {
        data() {
            return {
                message: "<!-- Messages page content goes here -->"
            };
        }
    }
</script>

<style lang="scss" scoped>

</style>
