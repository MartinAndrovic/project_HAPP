<template>

    <div>

        <div class="window-title"> your matches </div>

        <div v-if="matches.length === 0"> You have no matches yet</div>
        <div v-for="match in matches">
            <hr>
           your filter: {{ match.users_filter_name }} matched user: {{match.other_users_name}}
            <a :href="'/chat/' + match.other_users_id">
                <button class="btn btn-outline-warning btn-sm float-end"> send message to the user </button>
            </a>
            <br>
            <br>
            <hr>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            matches: []
        }
    },
    mounted() {
        this.getUsersMatches()
    },
    methods: {
        getUsersMatches() {
            axios.get(`/home/matches/`)
                .then(response => {
                    this.matches = response.data
                })
                .catch(error => console.log(error))
        },
    }
}
</script>

<style>
</style>
