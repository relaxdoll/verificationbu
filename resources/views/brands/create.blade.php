@extends('layouts.app', ['activePage' => 'indexVendor', 'titlePage' => __('Brand')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'},{'text':'Vendor','href':'/vendors'}, {'text':'Brand','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Brand" description="Adding a brand to our system.">
            <wizard-tab name="about" icon="eec-icons icon-store">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Brand Name" field="name" addon-left-icon="eec-icons icon-store"
                                    :vparam="['required']">
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
                    'property': 'brand',
                    'form': 'form',
                    'field': {
                        name: null,
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
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/brand', 'reset': true})
                        .then(response => {
                            Swal.fire({
                                title: 'Complete!',
                                text: "Brand has been successfully saved.",
                                icon: 'success',
                            })
                            console.log(response);
                        });
                }
            },
        });

    </script>
@endpush
