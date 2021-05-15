@extends('layouts.app', ['activePage' => 'indexWHTax', 'titlePage' => __('WHTax')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Account','href':'#'}]" active="WHTax"></breadcrumb>

        <topbutton text="Add Vehicle" link="/vehicle/create"></topbutton>

        <card collapseonselect v-show="showIndex">

            <cardheader title="WHTax Lists" count="whtax">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable collapseonselect property="whtax" :columns="tableColumn" :allowselect="true" :rowscrollableonselect="true">

                </darktable>

            </cardbody>
        </card>

        <whtax></whtax>

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
                    {'text': 'Bill Number', 'data': 'bill_number'},
                    {'text': 'Vendor', 'data': 'vendor_name'},
                    {'text': 'Due Date', 'data': 'due_date'},
                    {'text': 'Total', 'type': 'number', 'data': 'total'},
                    {'text': 'Modified', 'type': 'boolean', 'data': 'tax_detail'},
                    {'text': 'Print', 'type': 'custom', 'icon': 'eec-icons icon-print', 'align': 'center', 'tooltip': 'Print'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },
            },

            created() {
                this.$store.commit('setModel', 'whtax');

            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'showIndex',
                ]),
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'vehicle', 'is_group': true});
                },
            },
        });
    </script>
@endpush
