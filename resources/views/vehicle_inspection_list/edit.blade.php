@extends('layouts.app', ['activePage' => 'indexVehicle Inspection List', 'titlePage' => __('Edit Vehicle Inspection List')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Vehicle Inspection List','href':'/vehicle_inspection_list'}]" active="Edit"></breadcrumb>

        <topbutton text="Back" link="/vehicle_inspection_list"></topbutton>

        <wizard @update="update" title="Edit Vehicle Inspection List" description="Edit an existing vehicle in our system.">
            <wizard-tab name="about" icon="tim-icons  icon-bus-front-12">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="vehicle_inspection_list" class="row justify-content-center mt-5">
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
                id,

            },

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'vehicle_inspection_list',
                    'form': 'vehicle_inspection_list',
                    'field': {
                        vehicle_type_id: null,
                        name: null,
                        standard: null,
                    }
                });
            },

            mounted() {
                this.$store.dispatch('populateEditForm', {'form': 'vehicle_inspection_list', 'id': this.id});
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                update() {
                    this.$store.dispatch('update', {'form': 'vehicle_inspection_list', 'url': '/api/vehicle_inspection_list/'+ this.id})
                        .then(response => {
                            Swal.fire('Updated!', 'Vehicle Inspection List has been successfully updated.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
