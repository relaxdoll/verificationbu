@extends('layouts.app', ['activePage' => 'indexVendor', 'titlePage' => __('Vendor')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Vendor"></breadcrumb>

        <topbutton text="Add Vendor" link="/vendors/create"></topbutton>

        <card>

            <cardheader count="vendor" title="Vendor Lists">

                <gear>
                    <a class="dropdown-item" href="{{ route('brand.index') }}" style="cursor: pointer;">Brand</a>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable property="vendor" :columns="tableColumn">

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
                searchfield: null,
                tableColumn: [
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Name (TH)', 'data': 'nameTH'},
                    {'text': 'Email', 'data': 'email'},
                    {'text': 'Phone', 'data': 'phone'},
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
                    this.$store.dispatch('getTableData', {'property': 'vendor'});
                },

            },
        });
    </script>
@endpush
