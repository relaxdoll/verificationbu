<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    {{--    <link rel="shortcut icon" href="/img/oneibis.png" type="image/x-icon">--}}
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
                    <div v-if="width >= 768">
                        <!-- Start First Page -->
                        <div class="card mt-2">
                            <div class="position-absolute pt-5">
                                <img v-if="width >= 1294" src="/images/world_map.png" alt="" width="1294" height="464" style="opacity: 0.1">
                            </div>
                            <div class="card-body table-responsive" style="padding-top: 0">
                                <div class="text-center">
                                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">
                                </div>
                                <div class="d-flex justify-content-between align-items-end" style="padding-left: 50px;">
                                    <img src="/images/Bureau_Veritas_1828_logo.png" alt="" width="81" height="100">
                                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">
                                </div>
                                <table class="table table-borderless mb-5" style="margin-top: 20px;">
                                    <thead>
                                    <tr class="text-center" style="border-top: 2px solid #d01e34; border-bottom: 2px solid #a6a6a6; background-color: #d9d9d940">
                                        <th scope="col">DATE</th>
                                        <th scope="col">ACTIONS</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">DOCUMENTATION</th>
                                        <th scope="col" style="color: transparent">REPRESENTING/PERSON</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row"></th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">
                                        <th scope="row" style="color: black"><em>02/05/21</em></th>
                                        <td>1st INSPECTION STARTED</td>
                                        <td><em>PASSED</em></td>
                                        <td>DRAFT</td>
                                        <td scope="col"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"></th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">
                                        <th scope="row" style="color: black"><em>05/05/21</em></th>
                                        <td>2nd INSPECTION STARTED</td>
                                        <td><em>PASSED</em></td>
                                        <td>DRAFT</td>
                                        <td scope="col"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"></th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">
                                        <th scope="row" style="color: black"><em>15/05/21</em></th>
                                        <td>ISSUED REPORT</td>
                                        <td><em>PASSED</em></td>
                                        <td>FINAL TEST REPORT</td>
                                        <td scope="col"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="height: 50px; background-color: #d01e34; border-top-left-radius: 20px"></div>
                        </div>
                        <!-- End First Page -->

                        {{--        <!-- Start Second Page -->--}}
                        {{--        <div class="card mt-5">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body table-responsive" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <table class="table table-borderless mb-5" style="margin-top: 20px">--}}
                        {{--                    <thead>--}}
                        {{--                    <tr class="text-center" style="border-top: 2px solid #d01e34; border-bottom: 2px solid #a6a6a6; background-color: #d9d9d940">--}}
                        {{--                        <th scope="col">DATE</th>--}}
                        {{--                        <th scope="col">ACTIONS</th>--}}
                        {{--                        <th scope="col">STATUS</th>--}}
                        {{--                        <th scope="col">DOCUMENTATION</th>--}}
                        {{--                        <th scope="col">REPRESENTING/PERSON</th>--}}
                        {{--                    </tr>--}}
                        {{--                    </thead>--}}
                        {{--                    <tbody>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td>1st INSPECTION STARTED</td>--}}
                        {{--                        <td>PASSED</td>--}}
                        {{--                        <td>DRAFT</td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/04/21</th>--}}
                        {{--                        <td>2nd INSPECTION STARTED</td>--}}
                        {{--                        <td>PASSED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    </tbody>--}}
                        {{--                </table>--}}
                        {{--            </div>--}}
                        {{--            <div style="height: 50px;"></div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Second Page -->--}}

                        {{--        <!-- Start Third Page -->--}}
                        {{--        <div class="card mt-5">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body table-responsive" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <table class="table table-borderless mb-5" style="margin-top: 20px">--}}
                        {{--                    <thead>--}}
                        {{--                    <tr class="text-center" style="border-top: 2px solid #d01e34; border-bottom: 2px solid #a6a6a6; background-color: #d9d9d940">--}}
                        {{--                        <th scope="col">DATE</th>--}}
                        {{--                        <th scope="col">ACTIONS</th>--}}
                        {{--                        <th scope="col">STATUS</th>--}}
                        {{--                        <th scope="col">DOCUMENTATION</th>--}}
                        {{--                        <th scope="col">REPRESENTING/PERSON</th>--}}
                        {{--                    </tr>--}}
                        {{--                    </thead>--}}
                        {{--                    <tbody>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td>1st INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/04/21</th>--}}
                        {{--                        <td>2nd INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    </tbody>--}}
                        {{--                </table>--}}
                        {{--            </div>--}}
                        {{--            <div style="height: 50px;"></div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Third Page -->--}}

                        {{--        <!-- Start Forth Page -->--}}
                        {{--        <div class="card mt-5">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body table-responsive" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <table class="table table-borderless mb-5" style="margin-top: 20px">--}}
                        {{--                    <thead>--}}
                        {{--                    <tr class="text-center" style="border-top: 2px solid #d01e34; border-bottom: 2px solid #a6a6a6; background-color: #d9d9d940">--}}
                        {{--                        <th scope="col">DATE</th>--}}
                        {{--                        <th scope="col">ACTIONS</th>--}}
                        {{--                        <th scope="col">STATUS</th>--}}
                        {{--                        <th scope="col">DOCUMENTATION</th>--}}
                        {{--                        <th scope="col">REPRESENTING/PERSON</th>--}}
                        {{--                    </tr>--}}
                        {{--                    </thead>--}}
                        {{--                    <tbody>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td class="text-success">INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black"></th>--}}
                        {{--                        <td style="color: transparent">2nd INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black"></th>--}}
                        {{--                        <td style="color: transparent">2nd INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    </tbody>--}}
                        {{--                </table>--}}
                        {{--            </div>--}}
                        {{--            <div style="height: 50px;"></div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Forth Page -->--}}

                        {{--        <!-- Start Fifth Page -->--}}
                        {{--        <div class="card mt-5">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body table-responsive" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <table class="table table-borderless mb-5" style="margin-top: 20px">--}}
                        {{--                    <thead>--}}
                        {{--                    <tr class="text-center" style="border-top: 2px solid #d01e34; border-bottom: 2px solid #a6a6a6; background-color: #d9d9d940">--}}
                        {{--                        <th scope="col">DATE</th>--}}
                        {{--                        <th scope="col">ACTIONS</th>--}}
                        {{--                        <th scope="col">STATUS</th>--}}
                        {{--                        <th scope="col">DOCUMENTATION</th>--}}
                        {{--                        <th scope="col">REPRESENTING/PERSON</th>--}}
                        {{--                    </tr>--}}
                        {{--                    </thead>--}}
                        {{--                    <tbody>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td class="text-success">INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black">31/03/21</th>--}}
                        {{--                        <td style="color: transparent">2nd INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr>--}}
                        {{--                        <th scope="row"></th>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                    </tr>--}}
                        {{--                    <tr class="text-center" style="background-color: #d9d9d940; color: #2b3384">--}}
                        {{--                        <th scope="row" style="color: black"></th>--}}
                        {{--                        <td style="color: transparent">2nd INSPECTION STARTED</td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td></td>--}}
                        {{--                        <td scope="col"></td>--}}
                        {{--                    </tr>--}}
                        {{--                    </tbody>--}}
                        {{--                </table>--}}
                        {{--            </div>--}}
                        {{--            <div style="height: 50px;"></div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Fifth Page -->--}}

                    </div>

                    <div v-else>
                        <!-- Start First Page -->
                        <div class="card mt-2">
                            <div class="position-absolute pt-5">
                                <img v-if="width >= 1294" src="/images/world_map.png" alt="" width="1294" height="464" style="opacity: 0.1">
                            </div>
                            <div class="card-body" style="padding-top: 0">
                                <div class="text-center">
                                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">
                                </div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <img src="/images/Bureau_Veritas_1828_logo.png" alt="" width="81" height="100">
                                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">
                                </div>
                                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>
                                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">02/05/21</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>
                                        <div class="col-7 col-sm-8">1st INSPECTION STARTED</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>
                                        <div class="col-7 col-sm-8">PASSED</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>
                                        <div class="col-7 col-sm-8">DRAFT</div>
                                    </div>
                                    <div class="row">
                                        {{--                    <div class="col-5 col-sm-4" style="color: black">REPRESENTING/PERSON</div>--}}
                                        {{--                    <div class="col-7 col-sm-8">31/03/21</div>--}}
                                    </div>
                                </div>
                                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>
                                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">05/05/21</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>
                                        <div class="col-7 col-sm-8">2nd INSPECTION STARTED</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>
                                        <div class="col-7 col-sm-8">PASSED</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>
                                        <div class="col-7 col-sm-8">DRAFT</div>
                                    </div>
                                    <div class="row">
                                        {{--                    <div class="col-5 col-sm-4" style="color: black">REPRESENTING/PERSON</div>--}}
                                        {{--                    <div class="col-7 col-sm-8">31/03/21</div>--}}
                                    </div>
                                </div>
                                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>
                                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">16/05/21</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>
                                        <div class="col-7 col-sm-8">ISSUED REPORT</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>
                                        <div class="col-7 col-sm-8">PASSED</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>
                                        <div class="col-7 col-sm-8">FINAL TEST REPORT</div>
                                    </div>
                                    <div class="row">
                                        {{--                    <div class="col-5 col-sm-4" style="color: black">REPRESENTING/PERSON</div>--}}
                                        {{--                    <div class="col-7 col-sm-8">31/03/21</div>--}}
                                    </div>
                                </div>
                            </div>
                            <div style="height: 50px; background-color: #d01e34; border-top-left-radius: 20px"></div>
                        </div>
                        <!-- End First Page -->

                        {{--        <!-- Start Second Page -->--}}
                        {{--        <div class="card mt-2">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end py-2">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px;">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8">1st INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8">PASSED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8">DRAFT</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/04/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8">2nd INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8">PASSED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Second Page -->--}}

                        {{--        <!-- Start Third Page -->--}}
                        {{--        <div class="card mt-2">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end py-2">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px;">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8">1st INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/04/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8">2nd INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Third Page -->--}}

                        {{--        <!-- Start Forth Page -->--}}
                        {{--        <div class="card mt-2">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end py-2">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px;">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8 text-success">INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Forth Page -->--}}

                        {{--        <!-- Start Fifth Page -->--}}
                        {{--        <div class="card mt-2">--}}
                        {{--            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); padding-top: 80px ">--}}
                        {{--                <img src="/images/Bureau_Veritas_1828_logo.png" alt="" style="opacity: 0.1" width="300" height="370">--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body" style="padding-top: 0">--}}
                        {{--                <div class="text-center">--}}
                        {{--                    <img src="/images/customer_portal.png" alt="" width="178" height="100" style="opacity:0.5">--}}
                        {{--                </div>--}}
                        {{--                <div class="d-flex justify-content-end align-items-end py-2">--}}
                        {{--                    <img src="/images/bican_international.jpeg" alt="" width="142" height="50">--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px;">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8 text-success">INSPECTION STARTED</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div style="background-color: rgba(217, 217, 217, 0.25); color: rgb(43, 51, 132); padding: 5px; margin-top: 10px">--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Date</div>--}}
                        {{--                        <div class="col-7 col-sm-8" style="color: black; font-weight: bold; font-style: italic">31/03/21</div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Action</div>--}}
                        {{--                        <div class="col-7 col-sm-8 text-success"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Status</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">Documentation</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-5 col-sm-4" style="color: black; font-weight: bold;">REPRESENTING/PERSON</div>--}}
                        {{--                        <div class="col-7 col-sm-8"></div>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--        </div>--}}
                        {{--        <!-- End Fifth Page -->--}}

                    </div>
                    {{--                    <h2>Inspected Products search</h2>--}}

                    {{--                    <p>This database is at your disposal to: check a validity date and ensure the authenticity of an inspection.</p>--}}

                    {{--                    <div class="form-group" style="margin-bottom: 15px;">--}}
                    {{--                        <label for="autocomplete_certificate">Inspection Number</label>--}}
                    {{--                        <input type="text" class="autocomplete form-control" name="autocomplete_certificate" v-model="inspection_number" @keyup.enter="submit()">--}}
                    {{--                    </div>--}}

                    {{--                    <button type="button" class="btn" style="background-color: #337ab7; color: white;" @click="submit()">Search</button>--}}

                    {{--                    <p style="padding-top: 15px;">Where you can find the test report no./references on documents?&nbsp;</p>--}}
                    {{--                    <p>Certificat NF&nbsp;<span style="white-space: pre;"> <span style="white-space: pre;"> </span><span style="white-space: pre;"> </span></span>Certificat--}}
                    {{--                        GS&nbsp;<span style="white-space: pre;"> <span style="white-space: pre;"> <span style="white-space: pre;"> </span></span></span>Certificat IECE--}}
                    {{--                    </p>--}}
                    {{--                    <p><a href="images/nf_templates_-_before2017.png"><img src="/images/nf_templates_-_before2017.png" alt="" width="90" height="97"></a>&nbsp;<span--}}
                    {{--                            style="white-space: pre;"> </span> &nbsp;<span style="white-space: pre;"> <a href="images/nf_templates.png"><img--}}
                    {{--                                    src="/images/nf_templates.png" alt="" width="69" height="97"></a> <span style="white-space: pre;"> </span><span--}}
                    {{--                                style="white-space: pre;"> </span><span style="white-space: pre;"> </span><a href="images/gs_templates_-_before2017.png"><img--}}
                    {{--                                    src="/images/gs_templates_-_before2017.png" alt="" width="82" height="97"></a><span style="white-space: pre;"> <span--}}
                    {{--                                    style="white-space: pre;"> </span></span><a href="images/gs_templates.png"><img src="/images/gs_templates.png" alt="" width="69"--}}
                    {{--                                                                                                                    height="97"></a> <span--}}
                    {{--                                style="white-space: pre;"> <span style="white-space: pre;"> </span></span><a href="images/iecee_template.png"><img--}}
                    {{--                                    src="/images/iecee_template.png" alt="" width="68" height="97"></a></span>--}}
                    {{--                        <a href="images/test_report_template1.jpg"><img src="/images/test_report_template1.jpg" alt="" width="68" height="97"></a>&nbsp;<span--}}
                    {{--                            style="white-space: pre;"> </span><a href="images/test_report_template2.jpg">--}}
                    {{--                            <img src="/images/test_report_template2.jpg" alt="" width="68" height="97"></a>&nbsp;<span style="white-space: pre;"> </span></p>--}}
                    {{--                    <p><span style="white-space: pre;"><br></span></p>--}}
                    {{--                    <p>If you do not find an inspection or if you find different information, please send us:</p>--}}
                    {{--                    <ul>--}}
                    {{--                        <li>An inspection scanned</li>--}}
                    {{--                        <li>Contact information (email, telephone) of the person or company that provides you with the inspection</li>--}}
                    {{--                        <li>The coordinates of the contact to whom the information returns.</li>--}}
                    {{--                    </ul>--}}
                    {{--                    <p>VYou can contact us at: <a href="mailto:check.certificat@lcie.fr">verification@thbureauveritas.com</a></p>--}}
                    {{--                </div>--}}

                </div>
            </div>

        </div>

        <div id="page_contenu_right" style="margin-top: 5px; line-height: 36px; cursor: pointer">
            <div class="bloc_page" style="background-color: #dc0143; border-radius: 5px; border-bottom: 1px solid #999999">
                <a href="https://www.bureauveritas.co.th" class="p5" style="text-decoration: none; color: white">
                    <i class="fas fa-play-circle" style="color: white"></i>&nbsp; BV Homepage
                </a>
            </div>
            <div class="bloc_page mt-2" style="background-color: #dc0143; border-radius: 5px; border-bottom: 1px solid #999999">
                <a href="/images/CBGGT_BV_INSPECTIONTH_818.pdf" class="p5" style="text-decoration: none; color: white" download>
                    <i class="fas fa-download" style="color: white"></i>&nbsp; Download
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
                if (this.inspection_number && (this.inspection_number === 'IND 16-060/2136' || this.inspection_number === 'INS/TH-79/9564')) {
                    return window.location.href = window.location.origin + `/inspect_status`;
                } else {
                    swal("Error!", "The inspection number cannot be found", "error");
                }
            }
        },
        computed: {
            width: function () {
                return document.getElementById('page_contenu_centre').offsetWidth
                // return window.screen.width - 180 - 160;
            }
        },
        created() {
        },
        mounted() {
        },
    })
</script>
<script src=" {{ mix('/js/landing.js') }}"></script>

</html>
