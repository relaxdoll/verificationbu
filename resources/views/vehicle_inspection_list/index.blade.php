@extends('layouts.app', ['activePage' => 'indexVehicleInspectionList', 'titlePage' => __('Vehicle Inspection List')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Vehicle Inspection List"></breadcrumb>

        <topbutton text="Add Vehicle Inspection List" link="/vehicle_inspection_list/create"></topbutton>

        <card>

            <cardheader title="VehicleInspectionList Lists" count="vehicle_inspection_list">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                    <a class="dropdown-item" href="/settings/vehicle_inspection_list" style="cursor: pointer;">Settings</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="vehicle_inspection_list" :columns="tableColumn" :rowscrollableonselect="true">

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
                tableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Standard', 'data': 'standard'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },
            },

            created() {
                this.$store.commit('setModel', 'vehicle_inspection_list');
            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                ]),
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'vehicle_inspection_list', 'is_group': true});
                },
            },
        });
    </script>
@endpush
