<template lang="html">
    <BottomNavigation>
        <TabStrip>
            <TabStripItem class="navigation__item">
                <Label text="Servers"></Label>
                <Image src.decode="font://&#xf022;" class="fas t-36"></Image>
            </TabStripItem>
            <TabStripItem class="navigation__item">
                <Label text="Channel"></Label>
                <Image src.decode="font://&#xf4ad;" class="fas t-36"></Image>
            </TabStripItem>
            <TabStripItem class="navigation__item">
                <Label text="Members"></Label>
                <Image src.decode="font://&#xf0c0;" class="fas t-36"></Image>
            </TabStripItem>
        </TabStrip>

        <TabContentItem>
            <Frame id="items">
                <Items/>
            </Frame>
        </TabContentItem>

        <TabContentItem>
            <Frame id="browse">
                <Browse/>
            </Frame>
        </TabContentItem>

        <TabContentItem>
            <Frame id="search">
                <Search/>
            </Frame>
        </TabContentItem>

    </BottomNavigation>
</template>

<script>
    import Items from "./Items.vue";
    import Browse from "./Browse.vue";
    import Search from "./Search.vue";

    export default {
        components: {
            Items,
            Browse,
            Search
        }
    };
</script>

<style lang="scss">
    // Start custom common variables
    @import "~@nativescript/theme/scss/variables/ruby";
    // End custom common variables

    // Custom styles

</style>
