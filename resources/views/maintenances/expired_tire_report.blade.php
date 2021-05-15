@extends('layouts.app', ['activePage' => 'indexExpiredTireReport', 'titlePage' => __('Maintenance')])
@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Expired Tire Report"></breadcrumb>


    </div>

    <card collapseonselect>

        <cardheader count="expiredTire" title="Expired Tire Report">

            <gear>
                <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
            </gear>

        </cardheader>

        <cardbody>

            <darkgrouptable property="expiredTire" url="tire_placement/crud/activeTire" :columns="tableColumn"></darkgrouptable>

        </cardbody>
    </card>

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
                    {'text': 'License', 'data': 'license'},
                    {'text': 'Placement', 'data': 'placement'},
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Tire Type', 'data': 'tire_type'},
                    {'text': 'Tire Size', 'data': 'tire_size'},
                    {'text': 'Brand', 'data': 'brand'},
                ],
            },

            watch: {},

            created() {
            },

            computed: {
                ...mapState([
                    'showModal',
                ]),
            },

            mounted() {
            },

            methods: {},
        });
    </script>

@endpush
