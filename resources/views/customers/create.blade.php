@extends('layouts.app', ['activePage' => 'indexCustomer', 'titlePage' => __('Add Movement')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Movement','href':'/movement'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="/movement"></topbutton>

        <wizard @submit="submit" title="Create Movement" description="Follow the process to add a new movement to our system.">
            <wizard-tab name="movement" icon="eec-icons icon-distance">
                <h5 class="info-text"> Fill in to create fake movement.</h5>
                <v-form name="customer" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Place" field="place" addon-left-icon="tim-icons icon-istanbul"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Event" field="event" addon-left-icon="eec-icons icon-calendar-event-2"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Carrier" field="carrier" addon-left-icon="tim-icons  icon-email-85"
                                    :vparam="['']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-datepicker placeholder="Date" field="date" addon-left-icon="eec-icons icon-clock"
                                         :vparam="['required']">
                        </base-datepicker>
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
            },

            watch: {},


            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'customer',
                    'form': 'customer',
                    'field': {
                        date: null,
                        event: null,
                        place: null,
                        carrier: null,
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
                    this.$store.dispatch('submit', {'form': 'customer', 'url': '/api/movement', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Movement has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
