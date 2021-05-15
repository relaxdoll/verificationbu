<template>
    <div class="col-md-11">
        <div class="card">
            <div class="card-body" style="padding-top:40px">
                <wizard @submit="submit" title="Add Tire" description="Follow the process to add new tires to our system.">
                    <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                        <h5 class="info-text"> Pick the fleet which store the tires.</h5>

                        <v-form name="purchase">
                            <pill-input placeholder="" field="fleet_id" :options="fleetData"
                                        :vparam="['required']">
                            </pill-input>
                        </v-form>

                    </wizard-tab>
                    <wizard-tab name="purchase" icon="eec-icons icon-shop">
                        <h5 class="info-text"> Let's start with the purchase information.</h5>
                        <v-form name="purchase" class="row justify-content-center mt-5">
                            <div class="col-sm-5">
                                <base-input placeholder="PO No." field="purchase_order_number" addon-left-icon="tim-icons  icon-paper"
                                            :vparam="['required']" type="text">
                                </base-input>
                            </div>
                            <div class="col-sm-5">
                                <select-box field="vendor_id" placeholder="Vendor" type="select"
                                            url="vendor" optiontext="nameTH" optionvalue="id" addon-left-icon="eec-icons icon-shop"
                                            allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                            </div>
                            <div class="col-sm-5">
                                <select-box field="brand_id" placeholder="Brand" type="select"
                                            url="brand" optiontext="text" optionvalue="value" addon-left-icon="tim-icons icon-tag"
                                            allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                            </div>
                            <div class="col-sm-5">
                                <select-box field="type_id" placeholder="Type" type="select"
                                            :forceoption="typeData" optiontext="text" optionvalue="value" addon-left-icon="eec-icons icon-filter-tool"
                                            allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                            </div>
                            <div class="col-sm-5">
                                <base-datepicker placeholder="Date" field="date" addon-left-icon="eec-icons icon-clock"
                                                 :vparam="['required']">
                                </base-datepicker>
                            </div>
                            <div class="col-sm-5">
                                <base-input placeholder="Price" field="price" addon-left-icon="eec-icons  icon-money-11"
                                            :vparam="['required']" type="number">
                                </base-input>
                            </div>
                            <div class="col-sm-5">
                                <base-input placeholder="Quantity" field="amount" addon-left-icon="eec-icons  icon-hash-mark" @input="setQuantity($event)"
                                            :vparam="['required']" type="number">
                                </base-input>
                            </div>
                        </v-form>
                    </wizard-tab>
                    <wizard-tab name="account" icon="tim-icons icon-settings-gear-63">
                        <h5 class="info-text"> Lastly, key in the serials for all the tires.</h5>
                        <v-form name="tire" class="row justify-content-center mt-5">
                            <div class="col-sm-5" v-for="(tire, index) in forms.tire.tires" :key="index">
                                <base-input :placeholder="'Serial ('+index+')'" field="tires" :subfield="index" addon-left-icon="tim-icons icon-alert-circle-exc"
                                            :vparam="['required']"></base-input>
                            </div>
                        </v-form>
                    </wizard-tab>
                </wizard>
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
                typeData: [{'text': 'NEW', 'value': 1}, {'text': 'RE-TREAD', 'value': 2}],
                fleetData: [],
            }
        },
        watch: {
            rowSelected(value) {
                this.$store.commit('forceNavMini', value);
            },
        },

        created() {
            this.getFleet();
            this.$store.dispatch('populateForm', {
                'property': 'purchase',
                'form': 'purchase',
                'field': {
                    fleet_id: null,
                    brand_id: null,
                    vendor_id: null,
                    price: null,
                    type_id: null,
                    amount: null,
                    treadDepth: 16,
                    user_id: this.$store.state.user.id,
                    date: new Date(),
                    purchase_order_number: null,
                }
            });

            this.$store.dispatch('populateForm', {
                'property': 'tire',
                'form': 'tire',
                'field': {
                    initial_tread_depth: 16,
                    fleet_id: null,
                    purchase_id: null,
                    brand_id: null,
                    tire_type_id: null,
                    price: null,
                    tires: {},
                    is_available: 1,
                }
            });
        },

        computed: {
            ...mapState([
                'theme',
                'forms'
            ]),
        },

        mounted() {
            this.$children.forEach((child) => {
                if (child.$options.name === 'vForm') {
                    this.forms.push(child)
                }
            });
        },

        methods: {
            setQuantity(amount) {
                let tires = {};

                for (let i = 1; i <= amount; i++) {
                    tires[i] = null;
                }

                this.$store.commit('updateForm', {'form': 'tire', 'field': 'tires', 'value': tires});
            },
            getFleet() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/fleet', {})
                        .then(response => {
                            response.data.data.forEach((fleet) => {
                                this.fleetData.push({text: fleet.name, value: fleet.id});
                            });
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            submit() {

                this.$store.commit('updateForm', {
                    'form': 'tire',
                    'field': 'price',
                    'value': Math.round((this.forms.purchase.price / this.forms.purchase.amount) * 100) / 100
                });
                this.$store.commit('updateForm', {'form': 'tire', 'field': 'fleet_id', 'value': this.forms.purchase.fleet_id});
                this.$store.commit('updateForm', {'form': 'tire', 'field': 'brand_id', 'value': this.forms.purchase.brand_id});
                this.$store.commit('updateForm', {'form': 'tire', 'field': 'tire_type_id', 'value': this.forms.purchase.type_id});
                this.$store.dispatch('submit', {'form': 'purchase', 'url': '/api/purchase'})
                    .then(response => {

                        console.log(response);
                        this.$store.commit('updateForm', {'form': 'tire', 'field': 'purchase_id', 'value': response.data.id});
                        this.$store.dispatch('submit', {'form': 'tire', 'url': '/api/tire'})
                            .then(response => {
                                Swal.fire('Complete!', 'Tire has been successfully created.', 'success')
                                this.$store.commit('hideModal');
                                this.$store.commit('resetWizard', true);
                                console.log(response.data);

                            })
                            .catch(response => {
                                this.$store.dispatch('deleteForm', {'form': 'purchase', 'url': '/api/purchase/' + this.forms.tire.purchase_id})
                            });
                    });
            }
        },
    };
</script>
