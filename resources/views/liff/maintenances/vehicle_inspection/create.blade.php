@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Fast Track')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
    <style>
        .wheel {
            z-index: 3;
        }
    </style>
@endsection
@section('content')

    <div class="content">

        <liff-header title="Maintenance"></liff-header>

        <selector-icon :islast="false" :isgrouped="false" @click="openDrawer()" icon="icon icon-bus-front-10" iconcolor="#fc9500" label="ทะเบียนรถ"
                       style="margin-top: 20px;"></selector-icon>

        <liffdrawer2 @close="$store.commit('hideDrawer')" align="right" :maskclosable="true" :label="$store.state.drawer_name" :currentview="$store.page_name"
                     :closeable="true">
            <div v-if="$store.state.show_drawer">
                <liff-vehicle-inspection-create></liff-vehicle-inspection-create>
            </div>
        </liffdrawer2>
    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {},

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Vehicle Inspection');
            },


            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'driver_id',
                ]),
            },

            watch: {},

            methods: {
                openDrawer() {
                    this.$store.commit('showDrawer');
                    this.$store.state.drawer_name = 'ทะเบียนรถ';
                },
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
