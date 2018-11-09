<template>
    <div>
        <h3>Histories</h3>
        <div v-for="history in histories">
            {{ history.created_at }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "Histories",
        props: ['workflow_name', 'subject_id', 'subject_type'],
        data() {
            return {
                histories: [],
            };
        },
        mounted() {
            axios.get('/workflow/api/histories?workflow_name=' + this.workflow_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                this.histories = response.data;
            });
        },
    }
</script>

<style scoped>

</style>