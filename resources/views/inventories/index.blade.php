@extends('layouts.app', ['activePage' => 'indexInventory', 'titlePage' => __('Inventory')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <modal>

        <inventory-create></inventory-create>
    </modal>

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Inventory"></breadcrumb>

        <card collapseonselect>

            <cardheader count="inventory" title="Inventory Lists">

                <gear>
                    <a class="dropdown-item" href="{{ route('inventory_type.index') }}" style="cursor: pointer;">Type</a>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="inventory" :columns="tableColumn"  :rowscrollableonselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

    </div>

    <div style="bottom: 45px; right: 24px; position: fixed; ">
        <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
           @click="$store.commit('showModal', 'create-vehicle-type')">
            <i class="tim-icons icon-simple-add"></i>
        </a>
    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/maintenance_inventories.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                tableColumn: [
                    {'text': '#', 'type': 'index', 'notSortable': true},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Price', 'data': 'price'},
                    {'text': 'Quantity', 'data': 'quantity'},
                    {'text': 'Current Quantity', 'data': 'current_quantity'},
                    {'text': 'Type', 'data': 'inventory_type'},
                    {'text': 'Brand', 'data': 'brand'},
                    {'text': 'Available', 'type': 'boolean', 'data': 'is_available', 'align': 'center'},
                    {'text': 'Sold', 'type': 'boolean', 'data': 'is_sold', 'align': 'center'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'center', 'notSortable': true},
                ],
                rowIsSelected: false,
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.drivers.filter(newVal);
                }
            },

            created() {
                this.$store.commit('setModel', 'inventory');

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
                    this.$store.dispatch('getTableData', {'property': 'inventory', 'is_group': true});
                },
            },
        });
    </script>
@endpush
