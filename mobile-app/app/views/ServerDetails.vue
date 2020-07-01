<template>
    <Page>
        <ActionBar>
            <NavigationButton @tap="$navigateBack()" android.systemIcon="ic_menu_back"/>
            <Label :text="server.name"></Label>
        </ActionBar>

        <GridLayout>
            <Label class="m-10 h3" :text="server.name" verticalAlignment="top"></Label>
        </GridLayout>
    </Page>
</template>

<script>
    export default {
        props: ["server"]
    };
</script>

<style scoped lang="scss">

</style>
