@extends('layouts.app', ['activePage' => 'indexLineUser', 'titlePage' => __('Line')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'LINE','href':'#'}]" active="User"></breadcrumb>

        <div class="col-md-12">

            <card>

                <cardheader title="User Lists" :description="'Total: ' + dataCount">

                    <gear>
                        <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                    </gear>

                </cardheader>

                <cardbody>

                    <darktable property="line/user" :columns="tableColumn">

                    </darktable>

                </cardbody>
            </card>
        </div>

    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


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
                    {'text': 'user', 'data': 'lineName'},
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

            },
        });
    </script>
@endpush
