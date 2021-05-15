@extends('layouts.app', ['activePage' => 'indexVendor', 'titlePage' => __('Vendor')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Vendor','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Vendor" description="Adding a vendor to our system.">
            <wizard-tab name="about" icon="eec-icons icon-store">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Vendor Name" field="name" addon-left-icon="eec-icons icon-store"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Company Name (Thai)" field="nameTH" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Email" field="email" addon-left-icon="tim-icons  icon-email-85"
                                    :vparam="['email']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Phone" field="phone" addon-left-icon="tim-icons icon-mobile"
                                    :vparam="['integer', {'minLength': 10}]">
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
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'vendor',
                    'form': 'form',
                    'field': {
                        name: null,
                        nameTH: null,
                        email: null,
                        phone: null,
                        paymentType: null,
                        bankAccount: null,
                        user: this.$store.state.user,
                    }
                });
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                submit() {
                    this.$store.dispatch('submitFile', {'form': 'form', 'url': '/api/vendor', 'reset': true})
                        .then(response => {
                            Swal.fire({
                                title: 'Complete!',
                                text: "Vendor has been successfully saved.",
                                icon: 'success',
                            })
                        });
                }
            },
        });

    </script>
@endpush
