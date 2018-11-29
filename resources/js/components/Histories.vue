<template>
    <div class="card mb-3">
        <div class="card-header">
            流程历史
        </div>
        <div class="card-body">
            <div v-for="history in histories">
                {{ history.created_at }}
            </div>
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