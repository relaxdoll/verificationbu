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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <style>

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
                <a href="./"><img src="images/lien_home.png" alt="Home"/></a>&nbsp;&nbsp;
                <a href="http://lcie.fr"><img src="images/band_fr_on.png" alt="Fran&ccedil;ais"/></a>
            </div>
            <div class="pTop2"><a href="873/About-us/Contact-us/">Contact-us</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="663/about-us/map/">Map</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="953/bureau-veritas-group/">Bureau Veritas Group</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="504/news-and-events/sitemap/">Sitemap</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            </div>
        </div>

        <div id="search">
            <form action="recherche.php" method="post">
                <div class="toRight">&nbsp;<input type="image" src="images/btn_ok.png"/></div>
                <div><input type="text" name="search" value="Search" class="input_search" onclick="this.value='';"/></div>
            </form>
        </div>
        <div class="clear">&nbsp;</div>

        <div id="banniere_page">
            <img src="images/banniere_politique_qualite.jpg" height="106" width="981" alt="Bannière - Inspected products"/></div>

        <div id="mainNav">
            <ul class="first">
                <li><a class="home" href="https://www.lcie.com/en/"><img src="images/nav_main_home.png" width="20" height="27" alt="Accueil"/></a></li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  "><a href="628-about-us/lcie-bureau-veritas-overview.html">
                        About us</a>
                    <ul class="second hide">
                        <li><a href="629-about-us/history.html">
                                History</a></li>
                        <li><a href="630-about-us/organization.html">
                                Organization</a></li>
                        <li><a href="817-about-us/quality-policy.html">
                                Quality Policy</a></li>
                        <li><a href="942-about-us/impartiality.html">
                                Impartiality</a></li>
                        <li><a href="631-about-us/accreditations-&amp;-certifications.html">
                                Accreditations</a>
                            <ul class="second hide">
                                <li><a href="859-about-us/
								accreditations/certification-de-produits-industriels--en-45011-guide-isocei-65.html">
                                        Product Certification</a></li>
                                <li><a href="858-about-us/
								accreditations/accreditations--essais-nf-en-isocei-17025.html">
                                        Testing</a></li>
                                <li><a href="10345-about-us/
								accreditations/nf-en-isocei-170202012.html">
                                        Inspection</a></li>
                                <li><a href="1056-about-us/
								accreditations/our-accreditations.html">
                                        Our accreditations</a></li>
                            </ul>
                        </li>
                        <li><a href="658-about-us/recognitions.html">
                                Recognitions</a>
                            <ul class="second hide">
                                <li><a href="1057-about-us/
								-recognitions/our-recognitions.html">
                                        Our recognitions</a></li>
                            </ul>
                        </li>
                        <li><a href="818-about-us/partners.html">
                                Partners</a></li>
                        <li><a href="874-about-us/ethics-and-compliance.html">
                                Ethics and compliance</a></li>
                        <li><a href="873-about-us/contact-us.html">
                                Contact us</a></li>
                        <li><a href="663-about-us/visit-us.html">
                                Visit us</a></li>
                    </ul>
                </li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  "><a href="646-your-industry/your-industry.html">
                        Your industry</a>
                    <ul class="second hide">
                        <li><a href="929-your-industry/electric-and-hybrid-rechargeable-vehicles-.html">
                                Charging infrastructure</a></li>
                        <li><a href="637-your-industry/space-&amp;-defense.html">
                                Space and Defence</a></li>
                        <li><a href="709-your-industry/equipment.html">
                                Electrical apparatus</a></li>
                        <li><a href="695-your-industry/explosive-atmospheres-atex.html">
                                Explosive Atmospheres</a></li>
                        <li><a href="648-your-industry/automotive.html">
                                Automotive</a></li>
                        <li><a href="754-your-industry/cables-&amp;-ducts-.html">
                                Cables and conducts</a></li>
                        <li><a href="651-your-industry/medical-devices.html">
                                Medical devices</a></li>
                        <li><a href="717-your-industry/retailers-&amp;-importers.html">
                                Retailers and importers</a></li>
                        <li><a href="716-your-industry/domestic-appliances.html">
                                Household appliances</a>
                            <ul class="second hide">
                                <li><a href="804-your-industry/
								household-appliances/thermal-and-comfort-equipment.html">
                                        Thermal device</a></li>
                                <li><a href="915-your-industry/
								household-appliances/domestic-appliances.html">
                                        Household products</a></li>
                                <li><a href="725-your-industry/
								household-appliances/lighting.html">
                                        Lighting</a></li>
                            </ul>
                        </li>
                        <li><a href="653-your-industry/electronics-&amp;-radiocommunications.html">
                                Electronics & Radiocoms</a></li>
                        <li><a href="755-your-industry/renewable-energies.html">
                                Renewable energies</a>
                            <ul class="second hide">
                                <li><a href="930-your-industry/
								renewable-energies/batteries.html">
                                        Batteries</a></li>
                                <li><a href="931-your-industry/
								renewable-energies/hot-water-heat-pump.html">
                                        Hot water heat pump</a></li>
                                <li><a href="932-your-industry/
								renewable-energies/home-automation-and-energy-efficiency.html">
                                        Home automation and energy efficiency</a></li>
                                <li><a href="933-your-industry/
								renewable-energies/photovoltaics-and-other-sustainable-energies.html">
                                        Photovoltaics and other sustainable energies</a></li>
                            </ul>
                        </li>
                        <li><a href="650-your-industry/railway.html">
                                Rail</a></li>
                        <li><a href="652-your-industry/marine.html">
                                Marine industry</a></li>
                    </ul>
                </li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  "><a href="636-our-services/a-privileged-partner-at-your-service.html">
                        Our services</a>
                    <ul class="second hide">
                        <li><a href="10352-our-services/cybersecurity-program-.html">
                                Cybersecurity</a>
                            <ul class="second hide">
                                <li><a href="10369-our-services/
								cybersecurity/ioxt-certification.html">
                                        IOXT Certification</a></li>
                                <li><a href="10353-our-services/
								cybersecurity/iecee-62443-.html">
                                        IECEE 62443</a></li>
                                <li><a href="10354-our-services/
								cybersecurity/bureau-veritas-cybersecurity-certification-for-iot-devices-.html">
                                        Bureau Veritas cybersecurity certification for IoT devices </a></li>
                                <li><a href="10355-our-services/
								cybersecurity/type-certification-according-to-standards-&amp;-guidelines-.html">
                                        TYPE Certification according to Standards & Guidelines</a></li>
                                <li><a href="10356-our-services/
								cybersecurity/p-scan-known-vulnerabilities-assessment-pen-testing-.html">
                                        P-SCAN, known vulnerabilities assessment, Pen testing</a></li>
                                <li><a href="10357-our-services/
								cybersecurity/regulation-&amp;-privacy-data-management.html">
                                        Regulation & Privacy Data Management</a></li>
                                <li><a href="10358-our-services/
								cybersecurity/useful-link-cyber.html">
                                        Useful Link </a></li>
                                <li><a href="10359-our-services/
								cybersecurity/faq-cyber.html">
                                        FAQ</a></li>
                            </ul>
                        </li>
                        <li><a href="705-our-services/assistance-&amp;-expertise.html">
                                Assistance and expertise </a></li>
                        <li><a href="863-our-services/global-access.html">
                                Global access</a></li>
                        <li><a href="811-our-services/certification.html">
                                Certification</a>
                            <ul class="second hide">
                                <li><a href="810-our-services/
								certification/national-certification.html">
                                        National</a></li>
                                <li><a href="812-our-services/
								certification/european-certification.html">
                                        European</a></li>
                                <li><a href="865-our-services/
								certification/international-certification-.html">
                                        International</a></li>
                                <li><a href="1189-our-services/
								certification/certified-products.html">
                                        Inspected products</a></li>
                                <li><a href="868-our-services/
								certification/certification-rules.html">
                                        Certification Rules</a></li>
                            </ul>
                        </li>
                        <li><a href="789-our-services/sustainable-development.html">
                                Sustainable development</a></li>
                        <li><a href="638-our-services/tests-performed-on-your-electrical-equipments-and-electronics.html">
                                Testing</a>
                            <ul class="second hide">
                                <li><a href="701-our-services/ testing/emc-and-radio-testing.html"> EMC</a></li>
                                <li><a href="871-our-services/ testing/mechanical-and-climatic-testing-.html"> Mechanical / climatic</a></li>
                                <li><a href="704-our-services/ testing/electrical-safety-testing.html"> Electrical safety</a></li>
                            </ul>
                        </li>
                        <li><a href="654-our-services/training.html">
                                Training</a></li>
                        <li><a href="710-our-services/inspections-&amp;-audits.html">
                                Inspection and audits</a></li>
                        <li><a href="683-our-services/ce-marking.html">
                                CE marking</a></li>
                        <li><a href="697-our-services/regulation-&amp;-standards-watch.html">
                                Regulation & Standards Watch</a></li>
                    </ul>
                </li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  "><a href="661-locations/locations.html">
                        Locations</a>
                    <ul class="second hide">
                        <li><a href="660-locations/locations-in-the-paris-area.html">
                                Paris</a></li>
                        <li><a href="664-locations/for-the-north-east-of-france-the-pulversheim-site.html">
                                North-East</a></li>
                        <li><a href="662-locations/for-the-south-east-of-france-the-moirans-site.html">
                                South-East</a></li>
                        <li><a href="816-locations/offices-in-china.html">
                                China</a></li>
                    </ul>
                </li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  "><a href="864-online-tools/online-tools.html">
                        Online tools</a>
                    <ul class="second hide">
                        <li><a href="914-online-tools/certified-products.html">
                                Inspected Products Database</a></li>
                        <li><a href="819-online-tools/arene-users-access .html">
                                ARENE Users access</a></li>
                        <li><a href="10336-online-tools/useful-documents-atex.html">
                                Useful ATEX documents</a></li>
                    </ul>
                </li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
                <li class="  last  "><a href="655-careers/careers.html">
                        Careers</a></li>
                <li><img src="images/nav_main_separator.png" width="1" height="29" alt=""/></li>
            </ul>
        </div>

        <div id="page_contenu">

            <div id="page_contenu_left">&nbsp;
                <div class="mTop5"><a class="active" href="914-online-tools/certified-products.html">Inspected Products Database</a></div>
                <div class="mTop5"><a href="819-online-tools/arene-users-access .html">ARENE Users access</a></div>
                <div class="mTop5"><a href="10336-online-tools/useful-documents-atex.html">Useful ATEX documents</a></div>
            </div>

            <div id="page_contenu_centre" style="width:558px; border-right:1px solid #cacaca; ">
                <div class="mTop5"></div>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <link rel="stylesheet" href="/netapsys/bootstrap.css">
                <style>
                    .ui-autocomplete-loading {
                        background: white url("ui-anim_basic_16x16.gif") right center no-repeat;
                    }
                </style>
                <div class="container" id="container">

                    <h2>Inspected Products search</h2>

                    <p>This database is at your disposal to: check a validity date and ensure the authenticity of an inspection.</p>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="autocomplete_certificate">Inspection Number</label>
                        <input type="text" class="autocomplete form-control" name="autocomplete_certificate" id="autocomplete_certificate">
                    </div>

                    <form name="" action="" method="POST" class="form-group" style="margin-bottom: 15px;">
                        <input type="hidden" id="keyword_reference" name="keyword_reference" value="0">
                        <input type="hidden" id="keyword_brand" name="keyword_brand" value="0">
                        <input type="hidden" id="keyword_certificate" name="keyword_certificate" value="0">
                        <input type="submit" class="btn" style="background-color: #337ab7; color: white" value="Search">
                    </form>


                    <p>Where can you find the license numbers and product references on inspections?&nbsp;</p>
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
                                    src="/images/iecee_template.png" alt="" width="68" height="97"></a></span></p>
                    <p><span style="white-space: pre;"><br></span></p>
                    <p>If you do not find an inspection or if you find different information, please send us:</p>
                    <ul>
                        <li>An inspection scanned</li>
                        <li>Contact information (email, telephone) of the person or company that provides you with the inspection</li>
                        <li>The coordinates of the contact to whom the information returns.</li>
                    </ul>
                    <p>VYou can contact us at: <a href="mailto:check.certificat@lcie.fr">check.certificat@lcie.fr</a></p>
                </div>


            </div>

        </div>

        <div id="page_contenu_right">
            <div class="bloc_page">
                <div class="p5"><p><a href="/en/950/Our_services/Your_contacts_by_sector/"><img src="/images/btn_vos_contacts_uk.jpg" alt="" width="170" height="36"/></a>
                    </p></div>
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
        },
        methods: {},
        computed: {},
        created() {
        },
        mounted() {
        },
    })
</script>
<script src=" {{ mix('/js/landing.js') }}"></script>

</html>
