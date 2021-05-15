@extends('layouts.app', ['activePage' => 'indexVehicle', 'titlePage' => __('Vehicle')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <modal>

        <vehicle-type-create v-if="modalName === 'create-vehicle-type'"></vehicle-type-create>

        <payment-type-create v-if="modalName === 'create-payment-type'"></payment-type-create>

    </modal>

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Vehicle','href':'/vehicle'}]" active="Settings"></breadcrumb>

        <topbutton text="Back" link="/vehicle"></topbutton>

        <div class="col-md-12">
            <tabs>
                <tab name="Vehicle Type" icon="tim-icons icon-notes">
                    <vehicle-type></vehicle-type>
                </tab>
                <tab name="Payment Type" icon="tim-icons icon-money-coins">
                    <payment-type></payment-type>
                </tab>
                <tab name="Brand" icon="tim-icons icon-badge">
                </tab>
                <tab name="Document" icon="tim-icons icon-book-bookmark">
                </tab>
            </tabs>

        </div>

    </div>



@endsection

@push('js')
    <script src=" {{ mix('/js/vue/vehicle_settings.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                searchfield: null,
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.drivers.filter(newVal);
                }
            },


            computed: {
                ...mapState([
                    'modalName',
                    'forms',
                ]),
                dataCount() {
                    return this.$store.getters.getRowCount;
                },
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', 'customer');
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
