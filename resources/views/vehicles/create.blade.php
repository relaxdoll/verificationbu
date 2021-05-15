@extends('layouts.app', ['activePage' => 'indexVehicle', 'titlePage' => __('Add Vehicle')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Tracking No.','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Tracking No." description="This process add a tracking no. to our system.">
            <wizard-tab name="tracking" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Let's fool our customer.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="Booking No" field="booking_no" addon-left-icon="eec-icons icon-tags-stack"
                                    :vparam="[]">
                        </base-input>
                    </div>
                    <div class="col-sm-10">
                        <base-input placeholder="Container No" field="container_no" addon-left-icon="eec-icons icon-tags-stack"
                                    :vparam="[]">
                        </base-input>
                    </div>
                    <div class="col-sm-10">
                        <base-input placeholder="BL No" field="bl_no" addon-left-icon="eec-icons icon-tags-stack"
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
                        booking_no: null,
                        bl_no: null,
                        container_no: null,
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
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/tracking', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Tracking No has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
