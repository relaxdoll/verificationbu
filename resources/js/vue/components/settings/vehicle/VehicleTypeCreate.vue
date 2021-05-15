<template>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Vehicle Type</h4>
            </div>
            <div class="card-body">
                <v-form name="type" class="row">
                    <div class="col-sm-12" style="color:rgba(255,255,255,0.5); margin-bottom:10px;">
                        <pill-input placeholder="" field="type" :options="typeData" :nomt="true"
                                    :vparam="['required']">
                        </pill-input>
                    </div>
                    <div class="col-sm-12">
                        <base-input placeholder="Name" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-12">
                        <select-box field="no_of_wheels" placeholder="Number of wheels" type="select" :forceoption="[4,8,10,12]"
                                    url="line/user" optiontext="text" optionvalue="text" addon-left-icon="tim-icons  icon-support-17"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                </v-form>
            </div>
            <div class="card-footer" style="text-align: center;">
                <button class="btn btn-fill btn-primary" @click="submit()">Create</button>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'vehicle-type',
        data() {
            return {
                typeData: [{'text': 'HEAD', 'value': 1}, {'text': 'TAIL', 'value': 2}, {'text': 'STANDALONE', 'value': 3}],
                forms: [],
            }
        },
        watch: {
            rowSelected(value) {
                this.$store.commit('forceNavMini', value);
            },
        },

        created() {
            this.$store.dispatch('populateForm', {
                'property': 'type',
                'form': 'type',
                'field': {
                    name: null,
                    type: null,
                    no_of_wheels: null,
                }
            });

        },

        computed: {},

        mounted() {
        },

        methods: {
            submit() {

                let temp = true;

                this.forms.forEach((form) => {
                    temp = temp && form.validateForm();
                });

                if (temp){
                    this.$store.dispatch('submit', {'form': 'vehicle_type', 'url': '/api/setting/vehicle/type'})
                        .then(response => {
                            this.forms.forEach((form) => {
                                form.reset();
                            });
                            this.$store.commit('hideModal');
                            this.$store.state.refreshType = true;

                            console.log(response);
                        });
                }
            }
        },
    };
</script>
