@extends('layouts.app', ['activePage' => 'indexFasttrackBackground', 'titlePage' => __('FastTrack')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <liffloader :show="showSending" text="Initiating.."></liffloader>
    <liffloader :show="showConfirming" text="Confirming.."></liffloader>

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'FastTrack','href':'#'}, {'text':'Background','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create FastTrack Background" description="Adding a background to our system.">
            <wizard-tab name="background" icon="tim-icons icon-image-02">
                <h5 class="info-text"> Upload or paste a link to create FastTrack's background.</h5>

                <v-form name="background" class="row justify-content-center mt-5">
                    <div class="col-sm-10" v-show="!$store.state.forms.background.image">
                        <base-input placeholder="Link" field="link" addon-left-icon="tim-icons icon-link-72"
                                    :vparam="['url']">
                        </base-input>
                    </div>
                    <div style="text-align: center; margin: 20px 0;" class="col-sm-10" v-show="!$store.state.forms.background.link && !$store.state.forms.background.image">
                        - OR -
                    </div>
                    <div class="col-lg-10 text-center" style="margin-top: 10px;" v-show="!$store.state.forms.background.link">
                        <image-upload field="image" :require="(!$store.state.forms.background.link)"></image-upload>
                    </div>
                </v-form>

            </wizard-tab>
{{--            <wizard-tab name="test" icon="tim-icons icon-sound-wave">--}}
{{--                <h5 class="info-text"> Upload or paste a link to create FastTrack's background.</h5>--}}

{{--                <v-form name="background" class="row justify-content-center mt-5">--}}
{{--                    <div class="col-sm-10">--}}
{{--                        <base-input placeholder="Link" field="phone" addon-left-icon="tim-icons icon-link-72"--}}
{{--                                    :vparam="[]">--}}
{{--                        </base-input>--}}
{{--                    </div>--}}
{{--                </v-form>--}}

{{--            </wizard-tab>--}}
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
                showSending: false,
                showConfirming: false,
                initScrap: false,
                options: [],
                showSelect: false,
                reference: null,
                allowSubmit: false,
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'background',
                    'form': 'background',
                    'field': {
                        image: null,
                        link: null,
                        user: this.$store.state.user,
                    }
                });
                window.addEventListener('beforeunload', this.closeScrapper);
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                submit() {
                    this.$store.dispatch('submitFile', {'form': 'background', 'url': '/api/fast_track/background', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire({
                                title: 'Complete!',
                                text: "Background has been successfully saved.",
                                icon: 'success',
                            })
                        });
                }
            },
        });

    </script>
@endpush
