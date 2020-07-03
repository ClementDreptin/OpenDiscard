<template>
    <Page>
        <StackLayout>
            <Label :text="`Are you sure you want to ${actionTitle.toLowerCase()} this ${element}? This cannot be undone.`"
                   textWrap="true"/>
            <Button :text="actionTitle" @tap="deleteFunction" class="btn"/>
        </StackLayout>
    </Page>
</template>

<script>
    export default {
        name: "ConfirmDeleteModal",
        props: [
            'element',
            'actionTitle',
            'deleteFunction'
        ]
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    StackLayout {
        background-color: $grey-1;
        padding: 10;
    }

    Label {
        color: $white;
        font-size: 15;
    }

    .btn {
        margin-top: 18;
        height: 40;
        width: 50%;
        background-color: $grey-1;
        color: $danger;
        border-color: $danger;
        border-width: 2;
        border-radius: 5;
        font-size: 17;
        font-weight: 600;
    }
</style>
