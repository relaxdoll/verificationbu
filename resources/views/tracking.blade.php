<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="/img/oneibis.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>One Ibis Vessel Tracking</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>

    <link rel="stylesheet" href="{{ mix('css/landing.css') }}">

    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
          integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ mix('css/landing-font.css') }}">
    <link rel="stylesheet" href="{{ mix('css/landing-icon.css') }}">
    <style>
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
            color: white;
        }

        @media (max-width: 600px) {
            h3 {
                font-size: 20px;
            }

            h5 {
                font-size: 16px;
            }

            div {
                font-size: 12px;
            }

            button, span {
                font-size: 12px;
            }
        }


        .loader-20 > div {
            width: 100%;
        }

        .loader-20 div > div span {
            position: fixed;
            display: inline-block;
            height: 2px;
            width: 100%;
            border-radius: 50px;
            background-color: #0889FB;
            overflow: hidden;
            z-index: 10002;
        }

        .loader-20 > div > div span:before {
            content: '';
            position: absolute;
            top: 0;
            width: 40%;
            height: 100%;
            background-image: linear-gradient(to right, transparent, #333, transparent);
            animation: 0.7s moving_03 ease-in-out infinite;
        }

        @keyframes moving_03 {
            0% {
                left: -30%;
            }
            100% {
                left: 100%;
            }
        }

    </style>
</head>

<body id="skrollr-body">

<div id="app">
    <div class="loader loader-20" v-if="loading">
        <div>
            <div>
                <span></span>
            </div>
        </div>
    </div>

    <nav aria-label="breadcrumb" style="background-color: #e9ecef">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tracking</li>
            </ol>
        </div>
    </nav>

    <div class="container" v-if="detail">

        <div class="row section">
            <div :class="[windowWidth > 1000 ? 'col-8' : 'col-12']">
                <div class="section">
                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="card text-white mb-3 p-4"
                                 style="max-width: 1000px; max-height: 350px; border-radius: 10px; border: 1px solid white;background-image: linear-gradient(-45deg,#1488cc,#2b32b2);;">
                                <div class="card-body p-5">
                                    <h3 class="card-title text-center mb-5">Vessel departed @{{ detail.from }} on @{{ detail.depart }}, 2:18 a.m.</h3>
                                    <div class="row">
                                        <div class="col" style="border-right: 1px solid white">
                                            <i class="tracking-icon icon-square-pin"></i>
                                            Route:
                                            <div>
                                                @{{ detail.from }} -> @{{ detail.to }}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <i class="tracking-icon icon-opening-times"></i>
                                            Estimated time of arrival (ETA):
                                            <div style="color: #f6e05e;">
                                                @{{ detail.eta }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <i class="tracking-icon icon-loop"></i>
                                    Updated an hour ago
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="card border-light mb-3" style="max-width: 1000px; max-height: 350px; border-radius: 10px;">
                        <div class="card-header">Detail</div>

                        <div class="card-body ">
                            <div class="row">
                                <div class="col-4"> Booking Number</div>
                                <div class="col-1">:</div>
                                <div class="col">@{{ tracking.booking_no }}</div>
                            </div>
                            <div class="row">
                                <div class="col-4"> Container Number </div>
                                <div class="col-1">:</div>
                                <div class="col">@{{ tracking.container_no }}</div>
                            </div>
                            <div class="row">
                                <div class="col-4"> Bill of Lading Number</div>
                                <div class="col-1">:</div>
                                <div class="col">@{{ tracking.bl_no }}</div>
                            </div>
                            <div class="pt-3 text-right">

                                <button type="button" class="btn btn-outline-primary mr-2" style="width: 100px;">
                                    <a href="#down">
                                        <i class="tracking-icon icon-square-pin mt-1"></i>
                                        <span class="ml-1">LIVE MAP</span>
                                    </a>
                                </button>
                                <button @click="refresh()" type="button" class="btn btn-outline-primary" style="width: 100px;">
                                    <i class="tracking-icon icon-loop mt-1"></i>
                                    <span class="ml-1">UPDATE</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card border-light mb-3" style="max-width: 1000px; max-height: 350px; border-radius: 10px;">
                        <div class="card-header">Description</div>
                        <div class="card-body row">
                            <div class="col">
                                <div class="card-title">
                                    The current position of <b>ONE IBIS</b> is at coordinates <b>@{{ detail.lat }} N</b> / <b>@{{ detail.lng }} E</b>, reported an hour
                                    ago by AIS.
                                    The vessel is en route to the port of <b>@{{ detail.from }}</b>, sailing speed <b>@{{ getRandomArbitrary(20,21).toFixed(1) }}</b>
                                    knots and expected
                                    to arrive there on <b>@{{ detail.eta }}</b>.
                                </div>
                                <div>
                                    The vessel <b>ONE IBIS</b>(IMO: 9741384, MMSI 374815000) is a Container Ship built in 2016 (4 years old) and currently sailing under
                                    the flag
                                    <b>Panama</b>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="card border-light" >
                        <img style="border-radius: 3px;" src="/img/540281.jpg" alt="ONE IBIS">
                    </div>
                </div>

                <div class="section">
                    <h4>Container movements</h4>
                    <div class="card border-light mb-3" style="max-width: 1000px; border-radius: 10px;">
                        <table class="table table-striped">
                            <thead>
                            <tr class="text-primary">
                                <th scope="col">Date</th>
                                <th scope="col">Place</th>
                                <th scope="col">Event</th>
                                <th scope="col">Carrier</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(m,index) in movement" :key="index">
                                <th scope="row">@{{ m.date }}</th>
                                <td>@{{ m.place }}</td>
                                <td>@{{ m.event }}</td>
                                <td>
                                    <i v-if="m.carrier" class="tracking-icon icon-boat-front mt-1"></i>
                                    @{{ m.carrier }}
                                </td>
                            </tr>
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Fri, January 15, 2021</th>--}}
                            {{--                                <td>JEBEL ALI</td>--}}
                            {{--                                <td>Container will be delivered to consignee</td>--}}
                            {{--                                <td></td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Thu, January 14, 2021</th>--}}
                            {{--                                <td>JEBEL ALI</td>--}}
                            {{--                                <td>Container will be discharged from the vessel</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <i class="tracking-icon icon-boat-front mt-1"></i>--}}
                            {{--                                    HMM ALGECIRAS--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Fri, January 15, 2021</th>--}}
                            {{--                                <td>JEBEL ALI</td>--}}
                            {{--                                <td>Vessel will be under operation</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <i class="tracking-icon icon-boat-front mt-1"></i>--}}
                            {{--                                    HMM ALGECIRAS--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr class="table-warning">--}}
                            {{--                                <th scope="row">Wed, December 16, 2020</th>--}}
                            {{--                                <td>ROTTERDAM</td>--}}
                            {{--                                <td>Vessel departure time from this port</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <i class="tracking-icon icon-boat-front mt-1"></i>--}}
                            {{--                                    HMM ALGECIRAS--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Tue, December 15, 2020</th>--}}
                            {{--                                <td>ROTTERDAM</td>--}}
                            {{--                                <td>Container was loaded on the vessel</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <i class="tracking-icon icon-boat-front mt-1"></i>--}}
                            {{--                                    HMM ALGECIRAS--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Tue, December 08, 2020</th>--}}
                            {{--                                <td>ROTTERDAM</td>--}}
                            {{--                                <td>Container was located at this place</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <i class="tracking-icon icon-boat-front mt-1"></i>--}}
                            {{--                                    HMM ALGECIRAS--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Mon, December 07, 2020</th>--}}
                            {{--                                <td>ANTWERP</td>--}}
                            {{--                                <td>Empty container was released to shipper for stuffing</td>--}}
                            {{--                                <td></td>--}}
                            {{--                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="section" id="down">
                    <div class="card border-light mb-3" style="max-width: 1000px; max-height: 350px; border-radius: 10px;">
                        <iframe
                            :src="'https://maps.google.com/maps?q=' + detail.lat + ',' + detail.lng + '&hl=es&z=14&amp;output=embed'"
                            {{--                src="https://maps.google.com/maps?q='+YOUR_LAT+','+YOUR_LON+'&hl=es&z=14&amp;output=embed"--}}
                            width="auto" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>

            <div class="col ml-5" v-if="windowWidth > 1000">
                <div class="section">
                    <div style="background-color: #fdebc8; border-radius: 10px;">
                        <div class="p-3">
                            <div class="justify-content-center">
                                <img src="https://www.shipup.net/static/images/notification-subscription-banner-illustration.svg" alt="pic">
                            </div>
                            <div class="p-2 mt-3">
                                <h4> Automatically update anyone who is awaiting for delivery of this shipments</h4>
                            </div>
                            <div>
                                <i class="tracking-icon icon-handshake " style="font-size: 20px;"></i>
                                <span class="ml-1">Ensure Customer Satisfaction</span>
                            </div>
                            <div>
                                <i class="tracking-icon icon-money-time" style="font-size: 20px;"></i>
                                <span class="ml-1">Save Your Time</span>
                            </div>
                            <div class="p-2 mt-3">
                                <span> Shipup notifies you and anyone you add automatically any updae of your shipment</span>
                            </div>
                        </div>
                        <div class="p-3 text-center" style="cursor: pointer; background-color: #fbd38d">
                            TRY IT FOR FREE
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>

    <footer class="footer footer-big footer-color-black" id="footerTrigger">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    <div class="info add-animation-stopped animation-1">
                        <h5 class="title">Company</h5>
                        <nav>
                            <ul>
                                <li>
                                    <a href="/">
                                        OCEAN NETWORK EXPRESS (THAILAND) LTD.
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1 col-sm-3">
                    <div class="info add-animation-stopped animation-2">
                        <h5 class="title"> Business Hours</h5>
                        <nav>
                            <ul>
                                <li>
                                    <a name="#" style="cursor: default;">
                                        Mon-Sat
                                    </a>
                                </li>
                                <li>
                                    <a name="#" style="cursor: default;">
                                        8:30 AM - 5:30 PM
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3">
                    <div class="info add-animation-stopped animation-3">
                        <h5 class="title">Address</h5>
                        <nav>
                            <ul>
                                <li>
                                    <a name="#" style="cursor: default;">
                                        319 Chamchuri Square Building, 28th Floor,
                                        Phayathai Road, Pathumwan Bangkok 10330
                                        THAILAND                                    </a>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-1 col-sm-3">
                    <div class="info add-animation-stopped animation-4">
                        <h5 class="title">Contact</h5>
                        <nav>
                            <ul>
                                <li>
                                    <a name="#" style="cursor: default;">
                                        Email: info@oneibis.com
                                    </a>
                                </li>
                                <li>
                                    <a name="tel:+66942944299">
                                        Phone: +66(0)-2097-1111
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <hr>
            <div class="copyright">
                © 2019 OCEAN NETWORK EXPRESS - All Rights Reserved
            </div>
        </div>
    </footer>
</div>

</body>
<!--   core js files    -->
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

<script>

    new Vue({
        el: '#app',

        data: {
            windowWidth: window.innerWidth,
            detail: null,
            trackingDetail: null,
            loading: false,
            speed: null,
            movement: null,
            tracking
        },

        watch: {},


        created() {
            this.getDetail();
            this.getMovement();
        },

        mounted() {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })
        },

        beforeDestroy() {
            window.removeEventListener('resize', this.onResize);
        },

        methods: {
            getRandomArbitrary(min, max) {
                return Math.random() * (max - min) + min;
            },
            refresh() {
                this.loading = true;
                new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        resolve('foo');
                    }, 3000);
                })
                    .then(response => {
                        this.loading = false;
                        resolve(response.data);
                    })

            },
            getDetail() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/info/crud/get_current', {})
                        .then(response => {
                            this.detail = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });

            },
            getMovement() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/movement/crud/all', {})
                        .then(response => {
                            this.movement = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });

            },
            onResize() {
                this.windowWidth = window.innerWidth
            }
        },
    })
    ;
</script>

</html>
