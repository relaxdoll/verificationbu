<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="/img/logobv.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Bureau Veritas</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>

    <link rel="stylesheet" href="{{ mix('css/landing.css') }}">

    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
          integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css'>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-position: top;
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial;
        }

        input {
            border: none;
            background-image: -webkit-linear-gradient(top, rgb(237, 237, 237), rgb(255, 255, 255));
            height: 45px;
            font-size: 18px;
            color: #969696;
        }

        .input-container {
            display: -ms-flexbox; /* IE10 */
            display: flex;
            width: 100%;
            margin-bottom: 5px;
        }

        .icon {
            padding: 10px;
            background: #dfdfdf;
            color: white;
            min-width: 50px;
            text-align: center;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            outline: none;
        }

        .input-field:focus {
            border: none;
        }
    </style>
</head>

<body id="skrollr-body">

<div id="app">
    <div class="text-center">
        <div class="position-absolute m-auto"
             style="width: 325px; height: 631px; background-image: url('/images/login-background.png'); left: 0; right: 0; padding: 254px 11px 0px;">
            <div class="position-relative">
                <img src="/images/logobv.png" alt="logo.jpg">
                <div class="pb-3 " style="font-style: italic; color: #a9a2a4; padding-top: 33px; font-weight: 100">Please enter your login and your password</div>
                <div class="input-container">
                    <i class="fa fa-user icon" style="line-height: 25px"></i>
                    <input class="input-field" type="email" placeholder="Your email address" name="username" v-model="username">
                </div>

                <div class="input-container">
                    <i class="fa fa-key icon" style="line-height: 25px"></i>
                    <input class="input-field" type="password" placeholder="Password" name="password" v-model="password" @keyup.enter="submit()">
                </div>

                <div class="flex justify-content-center p-1">
                    {{--                    <button type="button" class="btn" style="min-width: 75px; background-color: #e9e9e9; border-bottom-color: #c6c6c6; margin-right: 3px;">Forgot your password?</button>--}}
                    <button type="button" class="btn" style="min-width: 75px; background-color: #d00038; color: white; font-size: 18px" @click="submit()">Login</button>
                </div>

                {{--                <div style="padding:10px 5px 5px 5px; text-decoration: underline; font-weight: bold">GDPR Request portal</div>--}}
            </div>
        </div>

    </div>
</div>

</body>
<!--   core js files    -->
<script>
    new Vue({
        el: '#app',
        data: {
            loading: false,
            isSelected: false,
            backgroundImageIndex: 1,
            username: null,
            password: null,
        },
        methods: {
            changeBackground() {
                window.onload = function () {

                    function changeImage() {
                        const BackgroundImg = ["./images/photo1.jpg", "./images/photo2.jpg", "./images/photo3.jpg", "./images/photo4.jpg"];
                        if (this.backgroundImageIndex < 3) {
                            this.backgroundImageIndex++;
                        } else {
                            this.backgroundImageIndex = 0;
                        }
                        document.body.style.backgroundImage = 'url("' + BackgroundImg[this.backgroundImageIndex] + '")';
                    }

                    window.setInterval(changeImage, 10000);
                }
            },
            submit() {
                const username = this.username ? this.username.toLowerCase() : null;
                if (username && this.password) {
                    if (username === 'relaxdoll@hotmail.com' || username === 'admin@inspection-bureauveritas.com' || username === 'ozawa@gaiacorp.com') {
                        if (this.password === 'System99!' || this.password === 'System99th') {
                            return window.location.href = window.location.origin + `/certified`;
                        } else {
                            swal("Error!", "Email or password did not match", "error");
                        }
                    } else {
                        swal("Error!", "Email or password did not match", "error");
                    }
                } else {
                    swal("Error!", "Please fill email and password", "error");
                }
            }
        },
        computed: {
            width: function () {
                return window.innerWidth > 0 ? window.innerWidth : screen.width
            }
        },
        created() {
            document.body.style.backgroundImage = 'url("' + "./images/photo1.jpg" + '")';
            this.changeBackground();
        },
        mounted() {
        },
    })
</script>
<script src=" {{ mix('/js/landing.js') }}"></script>

</html>
