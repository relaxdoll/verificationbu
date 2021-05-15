@extends('layouts.app', ['activePage' => 'indexVehicle', 'titlePage' => __('Tracking No.')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Tracking No."></breadcrumb>

        <topbutton text="Add Tracking No." link="/tracking/create"></topbutton>

        <card v-show="showIndex">

            <cardheader title="Tracking No. Lists" count="tracking">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable property="tracking" :columns="tableColumn">

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
                    {'text': 'Booking No', 'data': 'booking_no'},
                    {'text': 'BL No', 'data': 'bl_no'},
                    {'text': 'Container No', 'data': 'container_no'},
                    {'text': 'View', 'data': 'view'},
                    {'text': 'Last View', 'data': 'updated_at', 'align':'right'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },
            },

            created() {
                this.$store.commit('setModel', 'vehicle');

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
