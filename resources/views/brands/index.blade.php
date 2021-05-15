@extends('layouts.app', ['activePage' => 'indexVendor', 'titlePage' => __('Brand')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'},{'text':'Vendor','href':'/vendors'}]" active="Brand"></breadcrumb>

        <topbutton text="Add Brand" link="/brand/create"></topbutton>

        <card>

            <cardheader count="brand" title="Brand Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="brand" :columns="tableColumn" >

                </darkgrouptable>

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
                    {'text': '#', 'type': 'index'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Created Date', 'data': 'created_at'},
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
                    this.$store.dispatch('getTableData', {'property': 'brand'});
                },

            },
        });
    </script>
@endpush
