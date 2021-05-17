<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="/img/logobv.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Online tools | LCIE Bureau Veritas</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>

    <!--     Fonts and icons     -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/certified/global.css"/>
    <link rel="stylesheet" type="text/css" href="css/certified/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/certified/cookiebar.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .top-banner {
            background: #fff url('images/nav_main_bg.png') left center repeat-x;
            line-height: 29px;
            text-align: center;
            padding-right: 10px;
            font-family: Arial;
            font-size: 12px;
            cursor: pointer;
        }

        .top-banner a {
            text-decoration: none;
            color: #d01e34;
        }
    </style>
</head>

<body>

<div id="app" style="background-color: #dcdbdc">

    <div id="masque" class="hide"></div>

    <div id="contain">

        <div id="logo">
            <a href="./"><img id="logo_png" src="images/logo_lcie.png" alt="LCIE Logo"/></a>

        </div>
        <div id="titre_global">
            <h1>
                Inspected products
            </h1>
        </div>

        <div id="header_rubrique">
            <div class="toRight">
                <a href="https://www.bureauveritas.co.th/"><img src="images/lien_home.png" alt="Home"/></a>&nbsp;&nbsp;
            </div>
        </div>

        <div class="clear mb-5">&nbsp;</div>

        <div id="banniere_page">
            <img src="images/banniere_politique_qualite.jpg" height="106" width="981" alt="Bannière - Inspected products"/>
        </div>

        <div class="top-banner">
            <a href="https://www.bvna.com/environmental-laboratories/resources/customer-portal"> Access our lastest worldwide client portal page</a>
        </div>

        <div id="page_contenu">

            <div id="page_contenu_left">&nbsp;
                <div class="mTop5"><a class="active" href="/certified">Inspected Products Database</a></div>
            </div>

            <div id="page_contenu_centre" style="width:558px; border-right:1px solid #cacaca; ">
                <div class="mTop5"></div>
                <div class="container" id="container">

                    <h2>Inspected Products search</h2>

                    <p>This database is at your disposal to: check a validity date and ensure the authenticity of an inspection.</p>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="autocomplete_certificate">Inspection Number</label>
                        <input type="text" class="autocomplete form-control" name="autocomplete_certificate" v-model="inspection_number" @keyup.enter="submit()">
                    </div>

                    <button type="button" class="btn" style="background-color: #337ab7; color: white;" @click="submit()">Search</button>

                    <p style="padding-top: 15px;">Where you can find the test report no./references on documents?&nbsp;</p>
                    <p>Certificat NF&nbsp;<span style="white-space: pre;"> <span style="white-space: pre;"> </span><span style="white-space: pre;"> </span></span>Certificat
                        GS&nbsp;<span style="white-space: pre;"> <span style="white-space: pre;"> <span style="white-space: pre;"> </span></span></span>Certificat IECE
                    </p>
                    <p><a href="images/nf_templates_-_before2017.png"><img src="/images/nf_templates_-_before2017.png" alt="" width="90" height="97"></a>&nbsp;<span
                            style="white-space: pre;"> </span> &nbsp;<span style="white-space: pre;"> <a href="images/nf_templates.png"><img
                                    src="/images/nf_templates.png" alt="" width="69" height="97"></a> <span style="white-space: pre;"> </span><span
                                style="white-space: pre;"> </span><span style="white-space: pre;"> </span><a href="images/gs_templates_-_before2017.png"><img
                                    src="/images/gs_templates_-_before2017.png" alt="" width="82" height="97"></a><span style="white-space: pre;"> <span
                                    style="white-space: pre;"> </span></span><a href="images/gs_templates.png"><img src="/images/gs_templates.png" alt="" width="69"
                                                                                                                    height="97"></a> <span
                                style="white-space: pre;"> <span style="white-space: pre;"> </span></span><a href="images/iecee_template.png"><img
                                    src="/images/iecee_template.png" alt="" width="68" height="97"></a></span>
                        <a href="images/test_report_template1.jpg"><img src="/images/test_report_template1.jpg" alt="" width="68" height="97"></a>&nbsp;<span
                            style="white-space: pre;"> </span><a href="images/test_report_template2.jpg">
                            <img src="/images/test_report_template2.jpg" alt="" width="68" height="97"></a>&nbsp;<span style="white-space: pre;"> </span></p>
                    <p><span style="white-space: pre;"><br></span></p>
                    <p>If you do not find an inspection or if you find different information, please send us:</p>
                    <ul>
                        <li>An inspection scanned</li>
                        <li>Contact information (email, telephone) of the person or company that provides you with the inspection</li>
                        <li>The coordinates of the contact to whom the information returns.</li>
                    </ul>
                    <p>VYou can contact us at: <a href="mailto:check.certificat@lcie.fr">verification@thbureauveritas.com</a></p>
                </div>


            </div>

        </div>

        <div id="page_contenu_right" style="margin-top: 5px; line-height: 36px; cursor: pointer">
            <div class="bloc_page" style="background-color: #dc0143; ; border-radius: 5px; border-bottom: 1px solid #999999">
                <a href="https://www.bureauveritas.co.th" class="p5" style="text-decoration: none; color: white">
                    <i class="fas fa-play-circle" style="color: white"></i>&nbsp; BV Homepage
                </a>
            </div>
            <br/>
        </div>

        <div class="clear">&nbsp;</div>

        <div id="footer">&copy; Bureau Veritas Consumer Products Services, 2021 / <a
                href="https://www.bureauveritas.com/wps/wcm/connect/bv_com/Group/Home/About-Us/Our-Business/Our-Business-Consumer-Products/" target="_blank">Legal
                Disclaimer</a></div>


        <div id="lucmer"><a href="https://www.lucmer.fr" target="_blank"><img src="images/lucmer_logo.png" alt="lucmer communications"/></a></div>

    </div>

</div>

</body>
<!--   core js files    -->
<script>
    new Vue({
        el: '#app',
        data: {
            loading: false,
            inspection_number: null
        },
        methods: {
            submit() {
                if (this.inspection_number && (this.inspection_number === 'INS/TH-64/7915')) {
                    return window.location.href = window.location.origin + `/inspect_status`;
                } else {
                    swal("Error!", "The inspection number cannot be found", "error");
                }
            }
        },
        computed: {},
        created() {
        },
        mounted() {
        },
    })
</script>
<script src=" {{ mix('/js/landing.js') }}"></script>

</html>
