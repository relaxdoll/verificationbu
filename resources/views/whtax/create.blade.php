@extends('layouts.app', ['activePage' => 'indexVehicle', 'titlePage' => __('Add Vehicle')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Vehicle','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Vehicle" description="Follow the process to add a new vehicle to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for our new Vehicle.</h5>

                <v-form name="form">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="about" icon="tim-icons icon-bus-front-12">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="License" field="license" addon-left-icon="tim-icons icon-bus-front-12"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Odometer" field="mileage" addon-left-icon="tim-icons icon-user-run"
                                    :vparam="[]">
                        </base-input>
                    </div>
                    <div class="col-sm-10">
                        <select-box field="vehicle_type_id" placeholder="Vehicle Type" type="select"
                                    url="setting/vehicle/type" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-notes"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
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
                fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Laem Chabang', 'value': 3}, {'text': 'Suksawat', 'value': 2}],
            },

            watch: {},


            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'form',
                    'form': 'form',
                    'field': {
                        license: null,
                        fleet_id: null,
                        vehicle_type_id: null,
                        mileage: null,
                    }
                });
            },

            computed: {
                ...mapState([
                    'theme',
                ]),
            },

            methods: {
                submit() {
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/vehicle', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Vehicle has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
