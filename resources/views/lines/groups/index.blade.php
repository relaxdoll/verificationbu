@extends('layouts.app', ['activePage' => 'indexLineGroup', 'titlePage' => __('Line')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'LINE','href':'#'}]" active="Group"></breadcrumb>

        <topbutton text="Add Group" link="/lines/group/create"></topbutton>

        <card>

            <cardheader title="Group Lists" :description="'Total: ' + dataCount">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable property="line/group" :pill="{'property': 'fleet_id', 'data': fleetData, 'default': 1}"
                           :columns="tableColumn">

                </darktable>

            </cardbody>
        </card>

    </div>

@endsection

@push('js')
    @push('js')
        <script src="/js/vue/index.js"></script>


        <script>

            new Vue({
                el: '#asset',

                store,

                data: {
                    searchfield: null,
                    fleet: 1,
                    fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Suksawat', 'value': 2}, {'text': 'Laem Chabang', 'value': 3}],
                    tableColumn: [
                        {'text': '#', 'type': 'image', 'data': 'avatar', 'align': 'center'},
                        {'text': 'Name', 'data': 'name'},
                        {'text': 'Type', 'data': 'type'},
                        {'text': 'Active', 'data': 'isLinked'},
                        {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                    ],
                },

                watch: {
                    searchfield: function (newVal, oldVal) {
                        this.drivers.filter(newVal);
                    }
                },


                computed: {
                    dataCount() {
                        return this.$store.getters.getRowCount;
                    },
                },

                mounted() {
                },

                methods: {

                    refresh() {
                        this.$store.dispatch('getTableData', 'customer');
                    },

                    deleteData(id) {
                        if (confirm('{{ __("Are you sure you want to delete this driver?") }}')) {
                            return new Promise((resolve, reject) => {
                                axios.delete('/api/driver/' + id)
                                    .then(response => {
                                        notify('Driver deleted successfully', 'warning');
                                        this.drivers.get();
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
                    }
                },
            });
        </script>
    @endpush
