@extends('layouts.app', ['activePage' => 'indexInventory', 'titlePage' => __('Edit Inventory')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Inventory','href':'/inventory'}]" active="Edit"></breadcrumb>

        <topbutton text="Back" link="/inventory"></topbutton>

        <wizard @update="update" title="Edit Inventory" description="Edit an existing inventory in our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Change the fleet of our inventory.</h5>

                <v-form name="inventory">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="account" icon="tim-icons icon-settings-gear-63">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="inventory" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Current Quantity" field="current_quantity" addon-left-icon="tim-icons icon-single-02"
                                    :vparam="['required', {'maxValue': maxValue}]">
                        </base-input>
                    </div>
                </v-form>
            </wizard-tab>
        </wizard>
    </div>


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                id,
                fleetData: [],
                maxValue: null,
            },

            created() {
                this.getFleet();
                this.$store.dispatch('populateForm', {
                    'property': 'inventory',
                    'form': 'inventory',
                    'field': {
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

            mounted() {
                this.$store.dispatch('populateEditForm', {'form': 'inventory', 'id': this.id});
                this.getMaxQuantity();
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                getMaxQuantity() {
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/inventory/${id}/edit`, {})
                            .then(response => {
                                this.maxValue = response.data.data.current_quantity;
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });

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
                update() {
                    this.$store.dispatch('update', {'form': 'inventory', 'url': '/api/inventory/' + this.id})
                        .then(response => {
                            Swal.fire('Updated!', 'Inventory has been successfully updated.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
