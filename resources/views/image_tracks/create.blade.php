@extends('layouts.app', ['activePage' => 'indexImageTrack', 'titlePage' => __('Add Image Track')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Image Track','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Image Track" description="Follow the process to add an Image Track to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for this Image Track.</h5>

                <v-form name="form">
                    <pill-input placeholder="" field="fleet_id" :options="fleetData"
                                :vparam="['required']"></pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="about" icon="tim-icons icon-notes">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Title" field="title" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']"></base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Description" field="description" addon-left-icon="tim-icons icon-alert-circle-exc"
                                    :vparam="['required']"></base-input>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="image" icon="tim-icons icon-image-02">
                <h5 class="info-text"> Choose total image number and name each image.</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="image_number" placeholder="Image Number" type="select" :forceoption="options" @input="imageNumberSelected($event)"
                                    url="line/user" optiontext="text" optionvalue="text" addon-left-icon="tim-icons  icon-image-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5" v-for="index in forms.form.image_number">
                        <base-input :placeholder="'Image Title ('+index+')'" field="image_titles" :subfield="index" addon-left-icon="tim-icons icon-alert-circle-exc"
                                    :vparam="['required']" forceform="form"></base-input>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="link" icon="tim-icons icon-link-72">
                <h5 class="info-text"> Lastly, link customer to this image track (Optional).</h5>
                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="customer_id" placeholder="Customer (Optional)" type="multiselect"
                                    url="customer" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-istanbul" optiongroup="fleet"
                                    allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
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
                options: [1, 2, 3, 4, 5, 6, 7, 8, 9]
            },

            watch: {},


            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'form',
                    'form': 'form',
                    'field': {
                        title: null,
                        fleet_id: null,
                        description: null,
                        image_number: null,
                        customer_id: null,
                        type: 1,
                        image_titles: {},
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
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/image_track', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Driver has been successfully created.', 'success')
                        });
                },
                imageNumberSelected(count) {
                    let image_titles = {};

                    for (let i = 1; i <= count; i++) {
                        image_titles[i] = null;
                    }

                    this.$store.commit('updateForm', {'form': 'form', 'field': 'image_titles', 'value': image_titles});
                }
            },
        });
    </script>
@endpush
