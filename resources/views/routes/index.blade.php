@extends('layouts.app', ['activePage' => 'indexRoute', 'titlePage' => __('Route')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Route"></breadcrumb>

        <topbutton text="Add Route" link="/route/create"></topbutton>


        <card>

            <cardheader count="route" title="Route Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="route" :columns="tableColumn" @customclick="getTripRate($event)">

                </darkgrouptable>

            </cardbody>
        </card>

        <modal>
            <div class="card" style="padding: 30px; width: auto; background-color: #1e1e2f;">
                <darktable blank property="trip_rate" :columns="tripRateTableColumn">

                </darktable>
            </div>
        </modal>

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
                    {'text': '#', 'type': 'index'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Toll Fee', 'data': 'toll_fee'},
                    {'text': 'Incentive', 'data': 'incentive_price'},
                    {'text': 'Trip Rate', 'type': 'custom', 'icon': 'tim-icons icon-paper', 'align': 'center', 'if': 'isActive', 'tooltip': 'View'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'center', 'notSortable': true},
                ],
                tripRateTableColumn: [],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },

            },

            created() {
                this.$store.commit('setModel', 'route');

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
                assignTripRateTableColumn(data) {
                    this.tripRateTableColumn = [];
                    for (let datum in data) {
                        if (data[datum] && !datum.includes('id')) {
                            this.tripRateTableColumn.push({text: datum.replaceAll('_', ' '), data: datum});
                        }
                    }
                },
                getTripRate(id) {
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/trip_rate/crud/getTripRateById/${id}`, {})
                            .then(response => {
                                this.assignTripRateTableColumn(response.data[0]);
                                this.$store.commit("addTable", 'trip_rate');
                                this.$store.commit("addTableData", {'table_name': 'trip_rate', 'table_data': response.data});
                                this.$store.commit('showModal', 'trip_rate');
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'route', 'is_group': true});
                },

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this route?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/route/' + id)
                                .then(response => {
                                    notify('Driver deleted successfully', 'warning');
                                    this.routes.get();
                                    console.log(response.data);
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    console.log(error);
                                    reject(error.response);
                                });
                        });

                    } else {
                        console.log('no');
                    }
                },
            },
        });
    </script>
@endpush
