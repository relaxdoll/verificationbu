@extends('layouts.app', ['activePage' => 'indexTire', 'titlePage' => __('Edit Tire')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Tire','href':'/maintenance/tire'}]" active="Edit"></breadcrumb>

        <topbutton text="Back" link="/maintenance/tire"></topbutton>

        <wizard @update="update" title="Edit Tire" description="Edit an existing tire in our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Change the fleet of our tire.</h5>

                <v-form name="tire">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="status" icon="tim-icons icon-settings-gear-63">
                <h5 class="info-text"> Change tire status.</h5>
                <v-form name="tire">
                    <pill-input v-if="available" placeholder="" field="is_available" :options="statusData"
                                readonly :vparam="['required']">
                    </pill-input>

                    <pill-input v-else placeholder="" field="is_available" :options="statusData"
                                :vparam="['required']">
                    </pill-input>
                </v-form>
            </wizard-tab>
            <wizard-tab name="selling" icon="tim-icons icon-coins">
                <h5 class="info-text"> Is tire sold?</h5>
                <v-form name="tire" class="justify-content-center">
                    <pill-input placeholder="" field="is_sold" :options="isSoldData" :vparam="['required']"></pill-input>

                    <div class="col-sm-10 mt-5" v-if="forms.tire.is_sold === 1" style="margin: auto">
                        <base-input placeholder="Selling Price" field="selling_price" addon-left-icon="tim-icons icon-coins" :vparam="['required']"></base-input>
                    </div>
                </v-form>
            </wizard-tab>
        </wizard>
    </div>


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                id,
                statusData: [{'text': 'ใช้งานไม่ได้', 'value': 0}, {'text': 'ใช้งานได้', 'value': 1}],
                isSoldData: [{'text': 'ยังไม่ได้ขาย', 'value': 0}, {'text': 'ขายแล้ว', 'value': 1}],
                available: null,

            },

            created() {
                this.isTireStillAvailable(id);
                this.$store.dispatch('populateForm', {
                    'form': 'tire',
                    'property': 'tire',
                    'field': {
                        id: null,
                        fleet_id: null,
                        is_available: null,
                        is_sold: null,
                        selling_price: null,
                    }
                });
            },

            mounted() {
                this.$store.dispatch('populateEditForm', {'form': 'tire', 'id': this.id});
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                update() {
                    this.$store.dispatch('update', {'form': 'tire', 'url': '/api/tire/' + this.id})
                        .then(response => {
                            Swal.fire('Updated!', 'Tire has been successfully updated.', 'success')
                        });
                },
                isTireStillAvailable() {
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/tire/${this.id}/edit`, {})
                            .then(response => {
                                response.data.data.is_available === 1 ? this.available = true : this.available = false;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });

                },
            },
        });
    </script>
@endpush
