<template>
    <div>
        <div>EnabledTransitions</div>
        <div v-for="transition in transitions">
            {{ transition.title }}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrf_token', 'workflow_name', 'subject_id', 'subject_type'],
        name: "EnabledTransitions",
        data() {
            return {
                transitions: [],
            };
        },
        mounted() {
            axios.get('/workflow/api/transitions?workflow_name=' + this.workflow_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                this.transitions = response.data;
            });
        },
    }
</script>

<style scoped>

</style>