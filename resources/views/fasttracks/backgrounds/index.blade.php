@extends('layouts.app', ['activePage' => 'indexFasttrackBackground', 'titlePage' => __('FastTrack')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'FastTrack','href':'#'}]" active="Background"></breadcrumb>

        <topbutton text="Add Background" link="/fasttracks/backgrounds/create"></topbutton>

        <card>

            <cardheader title="Background Lists" count="background">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable @customclick="activate($event)" property="background" url="fast_track/background" :columns="tableColumn">

                </darktable>

            </cardbody>
        </card>

    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                tableColumn: [
                    {'text': '#', 'type': 'imageXL', 'data': 'link', 'align': 'center'},
                    {'text': 'Active', 'type': 'boolean', 'data': 'isActive', 'align': 'center'},
                    {'text': 'Activate', 'type': 'custom', 'icon': 'tim-icons icon-button-power', 'align': 'center', 'if': 'isActive', 'tooltip': 'Activate'},
                ],
            },

            watch: {},


            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'dataCount'
                ]),
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'background', 'url': 'fast_track/background'});
                },
                activate(id) {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/fast_track/background/crud/activateBackground/' + id)
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.refresh();
                                // console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                this.$store.commit('loading', false);
                                console.log(error);
                                reject(error.response);
                            });
                    });
                }

            },
        });
    </script>
@endpush
