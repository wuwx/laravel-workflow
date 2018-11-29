<template>
    <div class="box" v-if="transitions.length">
    <form action="/workflow/apply" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" v-model="csrf_token">
        <input type="hidden" name="workflow_name" v-model="workflow_name">
        <input type="hidden" name="subject_id" v-model="subject_id">
        <input type="hidden" name="subject_type" v-model="subject_type">
        <div class="box-header with-border">
            <div class="box-title">
                处理
            </div>
            <div class="box-tools">

            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <select v-model="transition_name" name="transition_name" class="form-control" @change="change">
                    <option v-for="transition in transitions" v-bind:value="transition.name">
                        {{ transition.title }}
                    </option>
                </select>
            </div>
            <div v-html="attributes"></div>
        </div>
        <div class="box-footer">
            <input type="submit" class="btn btn-primary pull-right">
        </div>
    </form>
    </div>
</template>

<style>
.cancel-off-png, .cancel-on-png, .star-half-png, .star-off-png, .star-on-png {
    font-size: 1.2em;
    color: #F90;
}
</style>

<script>
    export default {
        props: ['csrf_token', 'workflow_name', 'subject_id', 'subject_type'],
        data() {
            return {
                transition_name: '',
                transitions: [],
                attributes: '',
            };
        },
        mounted() {
            axios.get('/workflow/api/transitions?workflow_name=' + this.workflow_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                this.transitions = response.data;
            });
        },
        watch: {
            attributes: function () {
                $(function(){
                    $('.score').raty({
                        starType : 'i'
                    });
                });
            }
        },
        methods: {
            change() {
                axios.get('/workflow/attributes?workflow_name=' + this.workflow_name + '&transition_name=' + this.transition_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                    this.attributes = response.data;
                });
            },
        },
    }
</script>
