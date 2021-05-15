@extends('layouts.public', ['activePage' => 'indexRefuel', 'titlePage' => __('Refuel')])
@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="FastTrack"></breadcrumb>


        <card>

            <cardheader title="Fast Track Lists" :description="'Total: ' + dataCount">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable url="image_track_report/crud/nhp" property="fasttrack" :pill="{'property': 'fleet_id', 'data': fleetData, 'default': 1}"
                                :columns="tableColumn" :allowselect="true">

                </darkgrouptable>

            </cardbody>
        </card>


    </div>


    <modal>
        <div v-if="datum.image_array" style="width: 80vw !important;">
            <img v-for="(link, index) in datum.image_array" :key="index" :src="'https://eecl.s3-ap-southeast-1.amazonaws.com/'+link" alt="image" style="width:100%;">
        </div>
    </modal>
@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                fleet: 1,
                fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Suksawat', 'value': 2}, {'text': 'Laem Chabang', 'value': 3}],
                tableColumn: [
                    {'text': '#', 'data': 'id', 'align': 'center'},
                    {'text': 'Driver', 'data': 'driver'},
                    {'text': 'Vehicle', 'data': 'vehicle'},
                    {'text': 'Report', 'data': 'report'},
                    {'text': 'Date', 'data': 'date'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    if(value){
                        this.$store.commit('showModal', 'image_track');
                    }
                },
            },

            created() {
                this.$store.commit('setModel', 'image_track_report/crud/view');

            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum'

                ]),
                dataCount() {
                    return this.$store.getters.getRowCount;
                },
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', 'refuel/crud/group');
                },
            },
        });
    </script>
@endpush
