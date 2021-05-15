@extends('layouts.app', ['activePage' => 'indexCustomer', 'titlePage' => __('Movement')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'#'}]" active="Movement"></breadcrumb>

        <topbutton text="Add Movement" link="/movement/create"></topbutton>


        <card>

            <cardheader title="Movement Lists" count="movement">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable property="movement" :columns="tableColumn">

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
                    {'text': 'id', 'data':'id'},
                    {'text': 'Date', 'data': 'date'},
                    {'text': 'Place', 'data': 'place'},
                    {'text': 'Event', 'data': 'event'},
                    {'text': 'Carrier', 'data': 'carrier'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                ],
            },

            watch: {},


            computed: {},

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'movement', 'is_group': false});
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
