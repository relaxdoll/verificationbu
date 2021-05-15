@extends('layouts.app', ['activePage' => 'indexDriver', 'titlePage' => __('Info')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Info"></breadcrumb>

        <topbutton text="Add Info" link="/info/create"></topbutton>

        <card >

            <cardheader count="info" title="Info Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable @customclick="activate($event)"  property="info" :columns="tableColumn">

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
                    {'text': 'Latitude', 'data': 'lat'},
                    {'text': 'Longitude', 'data': 'lng'},
                    {'text': 'Depart From', 'data': 'from'},
                    {'text': 'Depart Time', 'data': 'depart'},
                    {'text': 'Arrive At', 'data': 'to'},
                    {'text': 'ETA', 'data': 'eta'},
                    {'text': 'Active', 'type': 'boolean', 'data': 'is_active', 'align': 'center'},
                    {'text': 'Activate', 'type': 'custom', 'icon': 'tim-icons icon-button-power', 'align': 'center', 'if': 'is_active', 'tooltip': 'Activate'},
                    {'text': 'Edit', 'type': 'action', 'data': 'phone', 'align': 'center', 'notSortable': true},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },

            },

            created() {
                this.$store.commit('setModel', 'driver');

            },

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
                    this.$store.dispatch('getTableData', {'property': 'info', 'is_group': false});
                },
                activate(id) {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/info/crud/activate/' + id)
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
