<template>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Payment Type</h4>
            </div>
            <div class="card-body">
                <v-form name="payment" ref="payment" class="row">
                    <div class="col-sm-12" style="color:rgba(255,255,255,0.5); margin-bottom:10px;">
                        <pill-input placeholder="" field="is_monthly" :options="typeData" :nomt="true"
                                    :vparam="['required']">
                        </pill-input>
                    </div>
                    <div class="col-sm-12">
                        <base-input placeholder="Name" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-12">
                        <select-box field="warning" placeholder="Warning before (Month)" type="select" :forceoption="[0,1,2,3,4,5,6]"
                                    url="line/user" optiontext="text" optionvalue="text" addon-left-icon="tim-icons icon-bell-55"
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
        name: 'payment-type',
        data() {
            return {
                typeData: [{'text': 'MONTHLY', 'value': 1}, {'text': 'ANNUALLY', 'value': 0}],
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
                'property': 'payment',
                'form': 'payment',
                'field': {
                    name: null,
                    is_monthly: null,
                    warning: null,
                }
            });

        },

        computed: {},

        mounted() {
        },

        methods: {
            submit() {

                this.$refs.payment.validateForm();
                if (this.$refs.payment.validated) {
                    this.$store.dispatch('submit', {'form': 'payment', 'url': '/api/setting/payment/type'})
                        .then(response => {
                            this.forms.forEach((form) => {
                                form.reset();
                            });
                            this.$store.commit('hideModal');
                            this.$store.state.refreshTable = true;

                            console.log(response);
                        });
                }
            }
        },
    };
</script>
