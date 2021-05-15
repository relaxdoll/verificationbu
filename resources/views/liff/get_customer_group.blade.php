@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Liff Starter')])

@section('style')
    {{--    <link href="/css/liff.css" rel="stylesheet"/>--}}
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('nav')
    {{--    @include('liff.qr.nav')--}}
@endsection
@section('content')

    <div class="content">

        <div class="code">@{{ code }}</div>

        <div style="position: fixed; bottom: 0;">
            <div style="text-align: center;">
                <p @click="getFlex()" href="#" class="liff-btn" style="background-color: #3B3736; color: #FFD25A; font-weight: 500;">ส่ง</p>
                <div style="width: 100vw; height: 30px; background-color: #3b3736;"></div>
            </div>

        </div>
    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            data: {
                // fast_track: new FastTrack(),
                flex: {},
                code,


            },

            watch: {
                // loaded() {
                //     this.getProfile();
                // }
            },


            created() {
                // this.getUserId('Ufacb069cbbe1593c753c043c903bfe8f');
                this.init();
                // this.fast_track.getInfo('U3094f16f5d2775edcaebca950e013091');
            },


            mounted() {
            },

            methods: {
                getFlex() {


                    liff.shareTargetPicker([
                        {
                            "type": "text",
                            "text": "Group link confirming.\r\nReference: " + this.code
                        }
                    ]);

                },

                init() {
                    liff.init({
                        liffId: '1653575237-aAbZBDo4'
                    }).then(function (response) {
                        // console.log('here');
                        if (!liff.isLoggedIn()) {
                            // set `redirectUri` to redirect the user to a URL other than the front page of your LIFF app.
                            liff.login({redirectUri: "https://maintenance.eecl.co.th/getCustomerGroup"});
                            // liff.login({redirectUri: "http://localhost:8000/liff?tab=forward&id=" + this.reportId});
                        } else {
                            console.log('logged in');
                        }
                        this.getFlex();
                        this.show = true;
                        this.loaded = true;
                    }.bind(this))
                },
                getProfile() {
                    liff.getProfile()
                        .then(function (profile) {
                            // this.profile = profile;
                            // this.lineId = profile.userId;
                            this.fast_track.getInfo(profile.userId);
                            this.showLoading = false;

                            // notify(this.lineId, 'success');
                        }.bind(this)).catch(function (error) {
                        notify('Please visit using LINE OA.', 'danger');
                        // window.alert('Error getting profile: ' + error);
                    });
                },
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
