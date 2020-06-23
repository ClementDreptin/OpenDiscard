<template>
    <div>
        <div v-if="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="Add someone to this Server"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keydown="fail = null" class="input" type="text" v-model="username" placeholder="Name">
                            </p>
                        </div>
                        <div class="field users-list">
                            <ul v-if="filteredUsers.length > 0">
                                <li v-for="user in filteredUsers" :key="user.id">
                                    <UserToAdd :user="user"/>
                                </li>
                            </ul>
                            <ul v-else>
                                <li class="not-found">Nobody was found...</li>
                            </ul>
                        </div>
                        <ErrorBox/>
                    </section>
                    <footer class="modal-card-foot">
                        <button @click="showModal = false" class="button is-success">Done</button>
                    </footer>
                </form>
            </div>
        </div>
        <div class="add-member-button">
            <span class="menu-label">Members</span>
            <a @click="showModal = true;username = ''">
                <span class="plus-sign"><i class="fa fa-plus"></i></span>
            </a>
        </div>
    </div>
</template>

<script>
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import UserToAdd from "./UserToAdd";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "AddMemberButton",
        components: {
            ErrorBox,
            ModalHeader,
            UserToAdd
        },
        data() {
            return {
                showModal: false,
                fail: null,
                username: "",
                users: [],
                filteredUsers: []
            }
        },
        methods: {
            searchUsers() {
                switch (this.username.trim().length) {
                    case 0:
                        this.users = [];
                        this.filteredUsers = [];
                        break;
                    case 1:
                        axios.get(`/users?elem=${this.username.trim()}`)
                            .then(response => {
                                this.users = response.data.users;
                                this.filteredUsers = this.users;
                            }).catch(err => errorHandler(err, this));
                        break;
                    default:
                        this.filteredUsers = this.users.filter(user => user.username.toLowerCase().startsWith(this.username.toLowerCase()));
                        break;
                }
            }
        },
        watch: {
            username: function() {
                this.searchUsers();
            }
        }
    }
</script>

<style scoped>
    .plus-sign {
        font-size: 0.6em;
    }

    .menu-list a:hover {
        background-color: transparent;
        color: #a5a5a5;
    }

    .add-member-button {
        display: flex;
    }

    .add-member-button > span {
        flex: 5;
        padding-top: 0.7em;
    }

    .add-member-button > a {
        text-align: right;
        flex: 1;
        color: #a5a5a5;
        font-size: 1.3rem;
        padding: 0;
    }

    .add-member-button > a:hover {
        color: white;
    }

    .users-list ul {
        background-color: #2F3136;
        margin-left: 0;
        margin-right: 0;
        border-radius: 3px;
        border: solid #b5b5b5 1px;
        height: 12rem;
        overflow-x: hidden;
        overflow-y: scroll;
        scrollbar-color: rgba(32,34,37,.6) transparent;
        scrollbar-width: thin;
    }

    .not-found {
        margin-top: 0.75em;
        color: #dcddde;
        opacity: 0.4;
    }
</style>