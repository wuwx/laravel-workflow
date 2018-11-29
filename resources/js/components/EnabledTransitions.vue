<template>
    <div class="card mb-3" v-if="transitions.length">
        <div class="card-body">
            <form action="/workflow/apply" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" v-model="csrf_token">
                <input type="hidden" name="workflow_name" v-model="workflow_name">
                <input type="hidden" name="subject_id" v-model="subject_id">
                <input type="hidden" name="subject_type" v-model="subject_type">
                <div class="form-group">
                    <label>处理</label>
                    <select v-model="transition_name" name="transition_name" class="form-control" @change="change">
                        <option v-for="transition in transitions" v-bind:value="transition.name">
                            {{ transition.title }}
                        </option>
                    </select>
                </div>
                <div class="form-group" v-for="(attribute, index) in attributes">
                    <template v-if="attribute.type=='hidden'">
                        <input type="hidden" :name="attribute.name" :value="_.get(attribute, 'options.value')">
                    </template>
                    <template v-else>
                        <label>{{ _.get(attribute, "options.label", attribute.name) }}</label>
                        <template v-if="attribute.type=='rate'">
                            <div class="form-control-plaintext">
                                <rate :name="attribute.name"></rate>
                            </div>
                        </template>
                        <template v-if="attribute.type=='select'">
                            <select :name="attribute.name" v-model="attributes[index].value" class="form-control">
                                <option v-for="(value, key) in _.get(attribute, 'options.choices')" :value="key">{{ value }}</option>
                            </select>
                        </template>
                        <template v-if="attribute.type=='text'">
                            <input :name="attribute.name" class="form-control">
                        </template>
                        <template v-if="attribute.type=='file'">
                            <input type="file" :name="attribute.name" class="form-control-plaintext">
                        </template>
                        <template v-if="attribute.type=='textarea'">
                            <textarea :name="attribute.name" class="form-control" rows="4"></textarea>
                        </template>
                        <template v-if="attribute.type=='static'">
                            {{ _.get(attribute, 'options.value') }}
                        </template>
                    </template>
                </div>
                <input type="submit" class="btn btn-primary pull-right">
            </form>
        </div>
    </div>
</template>

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
        methods: {
            change() {
                axios.get('/workflow/api/attributes?workflow_name=' + this.workflow_name + '&transition_name=' + this.transition_name + '&subject_id=' + this.subject_id + '&subject_type=' + this.subject_type).then(response => {
                    this.attributes = response.data;
                });
            },
        },
    }
</script>
