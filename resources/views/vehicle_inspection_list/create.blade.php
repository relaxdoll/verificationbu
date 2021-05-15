@extends('layouts.app', ['activePage' => 'indexVehicle Inspection List', 'titlePage' => __('Add Vehicle Inspection List')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Vehicle Inspection List','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Vehicle Inspection List" description="Follow the process to add a new vehicle to our system.">
            <wizard-tab name="about" icon="tim-icons icon-bus-front-12">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="vehicle_type_id" placeholder="ประเภทของรถ" type="select"
                                    url="setting/vehicle/type" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-notes"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="ชื่อรายการตรวจสภาพ" field="name" addon-left-icon="tim-icons icon-bullet-list-67"
                                    :vparam="['require']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="มาตรฐาน" field="standard" addon-left-icon="tim-icons icon-chart-bar-32"
                                    :vparam="[]">
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
                fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Laem Chabang', 'value': 3}, {'text': 'Suksawat', 'value': 2}],
            },

            watch: {},


            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'form',
                    'form': 'form',
                    'field': {
                        vehicle_type_id: null,
                        name: null,
                        standard: null,
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
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/vehicle_inspection_list', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Vehicle Inspection List has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
