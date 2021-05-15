@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Liff Starter')])

@section('style')
{{--    <link href="/css/liff.css" rel="stylesheet"/>--}}
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('nav')
{{--    @include('liff.qr.nav')--}}
@endsection
@section('content')

    <div class="content" v-if="show">

        <qrpurchase :tab="tabName" :profile="profile" :user="user_id"></qrpurchase>

    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    {{--<script src="/js/vue/liffrr.js"></script>--}}
    <script src=" {{ mix('/js/vue/liffsetting.js') }}"></script>
    <script src=" {{ mix('/js/vue/createQR.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            data: {
                tab,
                tabName,
                searchfield: null,
                // tab: 'create',
                // tabName: 'Create',
                profile: null,
                lineId: null,
                user_id: null,
                show: false,
                loaded: false,
            },

            watch: {
                loaded() {
                    this.getProfile();
                }
            },

            created() {
                // this.getUserId('Ufacb069cbbe1593c753c043c903bfe8f');
                this.init();
            },

            mounted() {
            },

            methods: {
                init() {
                    liff.init({
                        liffId: '1653723693-YDqZVXJ8'
                    }).then(function (response) {
                        // console.log('here');
                        this.show = true;
                        this.loaded = true;
                    }.bind(this))
                },
                sendMessage() {

                    liff.sendMessages([{
                        'type': 'text',
                        'text': "You've successfully sent a message! Hooray!"
                    }]).then(function (profile) {
                        liff.closeWindow();
                    }).catch(function (error) {
                        notify('error', 'danger');
                        // window.alert('Error getting profile: ' + error);
                    });
                },
                login() {
                    liff.login();
                },
                selectTab(tab) {
                    this.tab = tab;
                    switch (tab) {
                        case 'create':
                            this.tabName = 'Create';
                            break;
                        case 'purchase':
                            this.tabName = 'Purchase';
                            break;
                        case 'product':
                            this.tabName = 'My QR Code';
                            break;
                    }
                },
                labelClass(tab) {
                    if (this.tab == tab) {
                        return 'label-active'
                    } else {
                        return 'label'
                    }
                },
                iconClass(tab) {
                    if (this.tab == tab) {
                        return tab + '-active'
                    } else {
                        return tab
                    }
                },
                getProfile() {
                    liff.getProfile()
                        .then(function (profile) {
                            this.profile = profile;
                            this.lineId = profile.userId;
                            this.getUserId(this.lineId);

                            // notify(this.lineId, 'success');
                        }.bind(this)).catch(function (error) {
                        notify('Please visit using LINE OA.', 'danger');
                        // window.alert('Error getting profile: ' + error);
                    });
                },
                getUserId(lineId){
                    return new Promise((resolve, reject) => {
                        axios.get('/api/user', {
                            params: {
                                'lineId': lineId,
                            }
                        })
                            .then(response => {
                                this.user_id = response.data.data;
                                // notify(this.user_id, 'success');
                                console.log(response.data);
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
@endsection
