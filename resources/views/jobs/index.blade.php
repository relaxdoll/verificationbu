@extends('layouts.app', ['activePage' => 'indexJob', 'titlePage' => __('Job')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Job"></breadcrumb>

        <topbutton text="Add Job" link="/job/create"></topbutton>

        <card collapseonselect>

            <cardheader count="job" title="Job Lists">

                <gear>
                    <a class="dropdown-item" @click="refreshAvatar()" style="cursor: pointer;">Refresh Avatar</a>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable collapseonselect property="job" :columns="tableColumn" :allowselect="true" :rowscrollableonselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

        {{--        <jobdetail></jobdetail>--}}

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
                    {'text': 'First Name', 'data': 'firstName'},
                    {'text': 'Last Name', 'data': 'lastName'},
                    {'text': 'Vehicle', 'type': 'badge', 'data': 'vehicle'},
                    {'text': 'Tail', 'type': 'badge', 'data': 'tail'},
                    {'text': 'Phone', 'data': 'phone'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'center', 'notSortable': true},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },

            },

            created() {
                this.$store.commit('setModel', 'job');

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
                    this.$store.dispatch('getTableData', {'property': 'job', 'is_group': true});
                },
                refreshAvatar() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/job/crud/refreshProfileAvatar')
                            .then(response => {

                                this.$store.commit('loading', false);
                                this.$store.dispatch('getTableData', {'property': 'job', 'is_group': true});
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });

                },

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this job?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/job/' + id)
                                .then(response => {
                                    notify('Job deleted successfully', 'warning');
                                    this.jobs.get();
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
                },
            },
        });
    </script>
@endpush
