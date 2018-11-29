<template>
    <Steps :current="current">
        <Step v-for="step in steps" :title="step.title" :content="step.content"></Step>
    </Steps>
</template>
<script>
    export default {

        props: ['workflow_name', 'subject_id', 'subject_type'],

        data() {
            return {
                current: null,
                steps: [],
            };
        },
        mounted() {
            axios.get('/workflow/api/steps?workflow_name=' + this.workflow_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                this.steps = response.data.steps;
                this.current = response.data.current;
            });
        },
    }
</script>
