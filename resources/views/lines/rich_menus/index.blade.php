@extends('layouts.app', ['activePage' => 'indexLineRichMenu', 'titlePage' => __('Line')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'LINE','href':'#'}]" active="Rich Menu"></breadcrumb>

        <topbutton text="Add Menu" link="/lines/rich_menus/create"></topbutton>

        <card>

            <cardheader count="menu" title="Menu Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable @customclickraw="linkMenu($event)" url="line/richmenu" property="menu" :columns="tableColumn">

                </darktable>

            </cardbody>
        </card>

    </div>

    <modal>
        <div class="col-md-7">
            <div class="card card-user">
                <div class="card-body" style="padding-top: 40px;">
                    <div class="card-text">
                        <div class="author">
                            <div class="block block-one"></div>
                            <div class="block block-two"></div>
                            <div class="block block-three"></div>
                            <div class="block block-four"></div>
                            <a>
                                <base-image :src="link" style="height:200px;display: inline-flex; align-items: center; justify-content: center;"></base-image>
                            </a>
                        </div>
                    </div>
                    <v-form name="form" ref="form">
                        <div class="row justify-content-center mt-md">
                            <div class="col-md-8">
                                <select-box field="driver_id" placeholder="Driver" type="multiselect"
                                            url="driver" optiontext="name" optionvalue="lineId" optiongroup="fleet" addon-left-icon="tim-icons icon-single-02"
                                            allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
                            </div>
                            <div class="col-md-2">
                                <base-button style="margin:0 !important;" @click="submit()" type="primary">
                                    Link
                                </base-button>
                            </div>
                        </div>
                    </v-form>
                </div>
            </div>
        </div>
    </modal>

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
                    {'text': '#', 'type': 'imageXL', 'data': 'link', 'align': 'center'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Chat Bar', 'data': 'chatBarText'},
                    {'text': 'Size', 'data': 'size'},
                    {'text': 'Default', 'type': 'boolean', 'data': 'selected', 'align': 'center'},
                    {'text': 'Link', 'type': 'custom', 'icon': 'eec-icons icon-network-connection', 'align': 'center', 'tooltip': 'Link Menu'},
                ],
                link: null,
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.drivers.filter(newVal);
                }
            },


            computed: {
                ...mapState([
                    'forms',
                ]),
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'menu',
                    'form': 'form',
                    'field': {
                        id: null,
                        driver_id: null,
                    }
                });
            },

            mounted() {
            },

            methods: {

                submit() {
                    this.$refs.form.validateForm();
                    if (this.$refs.form.validated) {
                        this.$store.dispatch('submit', {'form': 'form', 'url': '/api/line/richmenu/crud/link'})
                            .then(response => {
                                this.$store.commit('resetForm', 'form');
                                this.$store.commit('hideModal');
                                console.log(response);
                            });
                    }
                },
                refresh() {
                    this.$store.dispatch('getTableData', 'customer');
                },

                linkMenu(datum) {
                    this.link = datum.link;
                    this.$store.commit('showModal');
                    this.$store.commit('updateForm', {'form': 'form', 'field': 'id', 'value': datum.id});
                    console.log(datum);
                },

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this driver?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/driver/' + id)
                                .then(response => {
                                    notify('Driver deleted successfully', 'warning');
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
