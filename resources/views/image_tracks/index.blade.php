@extends('layouts.app', ['activePage' => 'indexImageTrack', 'titlePage' => __('Image Track')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Image Track"></breadcrumb>

        <topbutton text="Add Image Track" link="/image_tracks/create"></topbutton>

        <card>

            <cardheader title="Image Track Lists" count="image_track">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="image_track" :pill="{'property': 'fleet_id', 'data': fleetData, 'default': 1}"
                                :columns="tableColumn">

                </darkgrouptable>

            </cardbody>
        </card>

    </div>


@endsection

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
                    // {'text': '#', 'type': 'image', 'data': 'avatar', 'align': 'center'},
                    {'text': 'Title', 'data': 'title'},
                    {'text': 'Description', 'data': 'description'},
                    {'text': 'Image Number', 'data': 'image_number'},
                    // {'text': 'Vehicle', 'type': 'badge', 'data': 'vehicle'},
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

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this fast track?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/lineReport/' + id)
                                .then(response => {
                                    notify('Fast Track deleted successfully', 'warning');
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
