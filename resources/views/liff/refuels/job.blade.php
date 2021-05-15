@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Liff Starter')])

@section('content')

    <div class="content">

        <div v-for="(list, index) in lists" :key="index">
            <p :class="'group'+index">@{{ list.group }}</p>
            <p @click="getFlex(list.job_id)" :class="'button'+index">share</p>
            <p @click="sent(list.job_id)" :class="'sent'+index">sent</p>
        </div>

        <div class="total">@{{ lists.length }}</div>

        <div @click="refresh()" class="refresh">refresh</div>

    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/versions/2.5.0/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            data: {
                // fast_track: new FastTrack(),
                lists: {},
                flex: {},


            },

            watch: {},


            created() {
                this.refresh();
            },

            mounted() {
                this.init();
            },

            methods: {
                refresh() {
                    axios.get('/api/refuelJob/')
                        .then(response => {

                            this.lists = response.data.data;
                            console.log(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                getFlex(id) {

                    return new Promise((resolve, reject) => {
                        axios.get('/api/refuelJob/' + id)
                            .then(response => {

                                liff.shareTargetPicker([
                                    response.data.data.flex
                                ]);

                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },

                sent(id) {

                    return new Promise((resolve, reject) => {
                        axios.get('/api/refuelJob/crud/sent/' + id)
                            .then(response => {


                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },

                init() {
                    liff.init({
                        liffId: '1653575237-ZQ2jgRYW'
                    }).then(function (response) {
                        // console.log('here');
                        if (!liff.isLoggedIn()) {
                            // set `redirectUri` to redirect the user to a URL other than the front page of your LIFF app.
                            liff.login({redirectUri: "https://maintenance.eecl.co.th/refuelJob"});
                            // liff.login({redirectUri: "http://localhost:8000/liff?tab=forward&id=" + this.reportId});
                        } else {
                            console.log('logged in');
                        }
                        this.show = true;
                        this.loaded = true;
                    }.bind(this))
                },
            },
        });
    </script>
@endsection
