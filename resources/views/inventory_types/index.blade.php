@extends('layouts.app', ['activePage' => 'indexInventory', 'titlePage' => __('Type')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'},{'text':'Inventory','href':'/inventory'}]" active="Type"></breadcrumb>

        <topbutton text="Back" link="/inventory"></topbutton>

        <card collapseonselect>

            <cardheader count="inventory_types" title="Type Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="inventory_types" :columns="tableColumn" :allowselect="true" :rowscrollableonselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

    </div>

    <div style="bottom: 45px; right: 24px; position: fixed; ">
        <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
           @click="$store.commit('showModal', 'create-inventory-type')">
            <i class="tim-icons icon-simple-add"></i>
        </a>
    </div>

    <modal>
        <inventory-type-create></inventory-type-create>
    </modal>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/maintenance_inventories.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                tableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Category', 'data': 'category'},
                    {'text': 'Has Serial', 'type': 'boolean', 'data': 'has_serial', 'align': 'center'},
                    {'text': 'Sellable', 'type': 'boolean', 'data': 'sellable', 'align': 'center'},
                    {'text': 'Quantable', 'type': 'boolean', 'data': 'quantable', 'align': 'center'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    this.$store.commit('forceNavMini', value);
                },

            },

            created() {
                this.$store.commit('setModel', 'inventory_types');

            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'dataCount',
                    'showModal',
                ]),
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'inventory_types', 'is_group': true});
                },
                refreshAvatar() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/inventory_types/crud/refreshProfileAvatar')
                            .then(response => {

                                this.$store.commit('loading', false);
                                this.$store.dispatch('getTableData', {'property': 'inventory_types', 'is_group': true});
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
                    if (confirm('{{ __("Are you sure you want to delete this inventory_types?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/inventory_types/' + id)
                                .then(response => {
                                    notify('InventoryTypes deleted successfully', 'warning');
                                    this.inventory_typess.get();
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
