@extends('layouts.liff', ['activePage' => 'indexMaintenance', 'titlePage' => __('Maintenance')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="banner" v-if="mode">
        <div class="row">
            <div :class="{'nav-active': mode === 'รายการซ่อมของฉัน'}" class="nav-text col-6 nav-box" @click="setMode('รายการซ่อมของฉัน')" style="padding-right: 0;">
                รายการซ่อมของฉัน
            </div>
            <div :class="{'nav-active': mode === 'แจ้งซ่อม'}" class="nav-text col-6 nav-box" @click="setMode('แจ้งซ่อม')" style="padding-left: 0;">
                แจ้งซ่อม
            </div>
        </div>
    </div>

    <div class="content" :class="{'has-banner':$store.state.has_banner}">

        <liff-header title="Maintenance"></liff-header>

        <liff-maintenance-approval-create></liff-maintenance-approval-create>

        <liff-maintenance-approval-my-request></liff-maintenance-approval-my-request>

    </div>




@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff_example.js') }}"></script>

    <script>
        new Vue({
            el: '#asset',

            store,

            data: {},

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Maintenance Approval');
                this.$store.commit('setMode', 'รายการซ่อมของฉัน');
                this.$store.commit('hasBanner');
            },

            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'driver_id',
                    'mode',
                    // 'approver_id',
                    // 'technician_id',
                ]),
            },

            watch: {},

            methods: {
                close() {
                    liff.closeWindow()
                },
                setMode(mode) {
                    if (mode === 'รายการซ่อมของฉัน') {
                        this.$store.commit('setMode', 'รายการซ่อมของฉัน');
                    } else {
                        this.$store.commit('setMode', 'แจ้งซ่อม');
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
