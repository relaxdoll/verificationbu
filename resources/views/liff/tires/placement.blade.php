@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Fast Track')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')
    <div class="banner">
        <div class="row">
            <div :class="{'nav-active': mode === 'เช็คลม/วัดดอก'}" class="nav-text col-6 nav-box" @click="setMode('เช็คลม/วัดดอก')" style="padding-right: 0;">
                เช็คลม/วัดดอก
            </div>
            <div :class="{'nav-active': mode === 'แจ้งเปลี่ยนยาง'}" class="nav-text col-6 nav-box" @click="setMode('แจ้งเปลี่ยนยาง')" style="padding-left: 0;">
                แจ้งเปลี่ยนยาง
            </div>
        </div>
    </div>

    <div class="content" :class="{'has-banner':$store.state.has_banner}">

        <liff-header title="Maintenance"></liff-header>

        <liff-tire-change-request></liff-tire-change-request>

        <liff-tire-pressure-and-tread></liff-tire-pressure-and-tread>

    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setMode', 'เช็คลม/วัดดอก');
                this.$store.commit('hasBanner');
                this.$store.commit('setPageName', 'Image Track');

            },

            mounted() {
            },

            computed: {
                ...mapState([
                    'mode',
                ]),
            },

            methods: {
                setMode(mode) {
                    this.$store.commit('setMode', mode)
                    if (mode === 'แจ้งเปลี่ยนยาง') {
                        this.$store.commit('setMode', 'แจ้งเปลี่ยนยาง');

                    } else {
                        this.$store.commit('setMode', 'เช็คลม/วัดดอก');
                    }
                },
                navClass(mode) {
                    if (mode === this.mode) {
                        return ' nav-active';
                    } else {
                        return 'col-6 nav-box';
                    }
                },
            },
        });
    </script>
@endsection
