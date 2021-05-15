@extends('layouts.app', ['activePage' => 'indexApprover', 'titlePage' => __('Approver')])

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Approver"></breadcrumb>

        <topbutton text="Add Approver" link="/approver/create"></topbutton>


        <card collapseonselect>

            <cardheader count="approver" title="Approver Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="approver" :columns="tableColumn" :allowselect="true" :rowscrollableonselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

        {{--        <approverdetail></approverdetail>--}}

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
                    {'text': '#', 'type': 'image', 'data': 'avatar', 'align': 'center', 'notSortable': true},
                    {'text': 'First Name', 'data': 'firstName'},
                    {'text': 'Last Name', 'data': 'lastName'},
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
                this.$store.commit('setModel', 'approver');

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
                    this.$store.dispatch('getTableData', {'property': 'approver', 'is_group': true});
                },

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this approver?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/approver/' + id)
                                .then(response => {
                                    notify('Driver deleted successfully', 'warning');
                                    this.approvers.get();
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
