<template>
    <figure class="image is-48x48">
        <a @click="addServer">
            <div>
                <svg width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M21 11.001H13V3.00098H11V11.001H3V13.001H11V21.001H13V13.001H21V11.001Z"></path>
                </svg>
            </div>
        </a>
    </figure>
</template>

<script>
    export default {
        name: "AddServerButton",
        methods: {
            addServer() {
                console.log("hello");
            }
        }
    }
</script>

<style scoped>
    a {
        padding: 0;
        border-radius: 10px;
        width: 48px;
        height: 48px;
    }

    svg {
        margin-top: 12px;
    }

    div:hover {
        border-radius: 10px;
        background-color: #43b581;
        color: white;
    }

    .menu-list a:hover {
        background-color: transparent;
        color: transparent;
    }

    div {
        color: #43b581;
        border-radius: 100%;
        width: 48px;
        height: 48px;
        background-color: #36393F;
        -moz-transition: all .3s;
        -o-transition: all .3s;
        -webkit-transition: all .3s;
        transition: all .3s;
    }
</style>