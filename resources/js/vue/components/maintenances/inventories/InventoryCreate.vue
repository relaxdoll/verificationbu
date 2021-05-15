<template>
    <div class="col-md-11">
        <div class="card">
            <div class="card-body" style="padding-top:40px">
                <wizard @submit="submit" title="Add Inventory" description="Follow the process to add new inventories to our system.">
                    <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                        <h5 class="info-text"> Pick the fleet which store the inventories.</h5>

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
                                <base-input placeholder="Name" field="name" addon-left-icon="tim-icons  icon-paper"
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
                                <select-box field="inventory_type_id" placeholder="Inventory Type" type="select"
                                            url="inventory_type" optiontext="text" optionvalue="value" addon-left-icon="eec-icons icon-filter-tool"
                                            allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                            </div>
                            <div class="col-sm-5">
                                <base-datepicker placeholder="Purchase Date" field="date" addon-left-icon="eec-icons icon-clock"
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
                        <div v-if="forms.inventory.has_serial">
                            <h5 class="info-text"> Lastly, key in the serials for all the inventories.</h5>
                            <v-form name="inventory" class="row justify-content-center mt-5">
                                <div class="col-sm-5" v-for="(inventory, index) in forms.inventory.inventories" :key="index">
                                    <base-input :placeholder="'Serial ('+index+')'" field="inventories" :subfield="index"
                                                addon-left-icon="tim-icons icon-alert-circle-exc"
                                                :vparam="['required']"></base-input>
                                </div>
                            </v-form>
                        </div>
                        <div v-else>
                            <h5 class="info-text"> Please Create.</h5>
                        </div>
                    </wizard-tab>
                </wizard>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'inventory-create',
        data() {
            return {
                fleetData: [],
            }
        },
        watch: {
            forms: {
                deep: true,
                handler(val) {
                    if (val.purchase.inventory_type_id) {
                        this.checkInventoryTypeSerial(val.purchase.inventory_type_id);
                    }
                },
            },

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
                    inventory_type_id: null,
                    amount: null,
                    user_id: this.$store.state.user.id,
                    date: null,
                    purchase_order_number: null,
                    name: null,
                }
            });

            this.$store.dispatch('populateForm', {
                'property': 'inventory',
                'form': 'inventory',
                'field': {
                    name: null,
                    price: null,
                    quantity: null,
                    current_quantity: null,
                    inventory_type_id: null,
                    brand_id: null,
                    purchase_id: null,
                    fleet_id: null,
                    inventories: {},
                    has_serial: true,
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
            checkInventoryTypeSerial(id) {
                return new Promise((resolve, reject) => {
                    axios.get(`api/inventory_types/crud/hasSerial/${id}`, {})
                        .then(response => {
                            this.$store.commit('updateForm', {'form': 'inventory', 'field': 'has_serial', 'value': response.data.data});
                            // console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },

            setQuantity(amount) {
                let inventories = {};

                for (let i = 1; i <= amount; i++) {
                    inventories[i] = null;
                }

                this.$store.commit('updateForm', {'form': 'inventory', 'field': 'inventories', 'value': inventories});
            },
            assignQuantity() {
                if (this.forms.inventory.has_serial) {
                    this.$store.commit('updateForm', {'form': 'inventory', 'field': 'quantity', 'value': 1});
                    this.$store.commit('updateForm', {'form': 'inventory', 'field': 'current_quantity', 'value': 1});
                } else {
                    this.$store.commit('updateForm', {'form': 'inventory', 'field': 'quantity', 'value': this.forms.purchase.amount});
                    this.$store.commit('updateForm', {'form': 'inventory', 'field': 'current_quantity', 'value': this.forms.purchase.amount});
                }
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
                    'form': 'inventory',
                    'field': 'price',
                    'value': Math.round((this.forms.purchase.price / this.forms.purchase.amount) * 100) / 100
                });
                this.assignQuantity();
                this.$store.commit('updateForm', {'form': 'inventory', 'field': 'name', 'value': this.forms.purchase.name});
                this.$store.commit('updateForm', {'form': 'inventory', 'field': 'fleet_id', 'value': this.forms.purchase.fleet_id});
                this.$store.commit('updateForm', {'form': 'inventory', 'field': 'brand_id', 'value': this.forms.purchase.brand_id});
                this.$store.commit('updateForm', {'form': 'inventory', 'field': 'inventory_type_id', 'value': this.forms.purchase.inventory_type_id});

                this.$store.dispatch('submit', {'form': 'purchase', 'url': '/api/purchase'})
                    .then(response => {

                        console.log(response);
                        this.$store.commit('updateForm', {'form': 'inventory', 'field': 'purchase_id', 'value': response.data.id});
                        this.$store.dispatch('submit', {'form': 'inventory', 'url': '/api/inventory'})
                            .then(response => {

                                this.$store.commit('hideModal');
                                this.$store.commit('resetWizard', true);
                                console.log(response.data);

                            })
                            .catch(response => {
                                this.$store.dispatch('deleteForm', {'form': 'purchase', 'url': '/api/purchase/' + this.forms.inventory.purchase_id})
                            });
                    });
            }
        },
    };
</script>
