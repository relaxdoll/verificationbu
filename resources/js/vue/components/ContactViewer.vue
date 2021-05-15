<template>
    <div style="padding-top: 5%; background-color: white;">

        <img src="/icon/vcard/add.png" style="height: 75px; position: fixed; left: 75%; bottom: 10%; z-index: 1000" @click="generateVCard">

        <div style="text-align: center; margin-top: 10px;"><img :src="vcard.image" class="profile"></div>

        <div class="row">
            <div class="col-12" style="text-align: center; margin-top: 20px; font-size: 28px; font-weight: 400; color: black;">{{vcard.firstname}} {{vcard.lastname}}
            </div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-2"></div>
            <div class="col-2" style="text-align: center; padding: 0">
                <img @click="clickMessage()" src="/icon/vcard/message.png" alt="error" style="height: 50px;">
            </div>
            <div class="col-2" style="text-align: center; padding: 0">
                <img @click="clickCall()" src="/icon/vcard/call.png" alt="error" style="height: 50px;">
            </div>
            <div class="col-2" style="text-align: center; padding: 0">
                <img @click="clickVideo()" src="/icon/vcard/video.png" alt="error" style="height: 50px;">
            </div>
            <div class="col-2" style="text-align: center; padding: 0">
                <img @click="clickMail()" src="/icon/vcard/mail.png" alt="error" style="height: 50px;">
            </div>
            <div class="col-2"></div>
        </div>

        <liffgroup>
            <div v-show="vcard.phone != null, vcard.phone != '' " class="select-option-first">
                <div class="detail-header">phone</div>
                <div class="detail-content">{{vcard.phone}}</div>
            </div>
            <div v-show="vcard.email != null, vcard.email != '' " class="select-option">
                <div class="detail-header">email</div>
                <div class="detail-content">{{vcard.email}}</div>
            </div>
            <div v-show="vcard.company != null, vcard.company != '' " class="select-option">
                <div class="detail-header">company</div>
                <div class="detail-content">{{vcard.company}}</div>
            </div>
            <div v-show="vcard.jobtitle != null, vcard.jobtitle != '' " class="select-option">
                <div class="detail-header">title</div>
                <div class="detail-content">{{vcard.jobtitle}}</div>
            </div>
            <div v-show="vcard.address != null, vcard.address != '' " class="select-option">
                <div class="detail-header">address</div>
                <div class="row detail-content-address" style="padding-top: 5px;">
                    <p v-show="vcard.address.building_number != null, vcard.address.building_number != '' ">{{vcard.address.building_number}}, &thinsp;</p>
                    <p v-show="vcard.address.building_floor != null, vcard.address.building_floor != '' ">{{vcard.address.building_floor}} Fl., &thinsp;</p>
                    <p v-show="vcard.address.building_name != null, vcard.address.building_name != '' ">{{vcard.address.building_name}}</p>
                </div>
                <div class="row detail-content-address">
                    <p v-show="vcard.address.moo != null, vcard.address.moo != '' ">{{vcard.address.moo}}, &thinsp;</p>
                    <p v-show="vcard.address.soi != null, vcard.address.soi != '' ">{{vcard.address.soi}}, &thinsp;</p>
                    <p v-show="vcard.address.road != null, vcard.address.road != '' ">{{vcard.address.road}}</p>
                </div>
                <div class="row detail-content-address">
                    <p v-show="vcard.address.sub_district != null, vcard.address.sub_district != '' ">{{vcard.address.sub_district}}, &thinsp;</p>
                    <p v-show="vcard.address.district!= null, vcard.address.district != '' ">{{vcard.address.district}}</p>
                </div>
                <div class="row detail-content-address">
                    <p v-show="vcard.address.province != null, vcard.address.province != '' ">{{vcard.address.province}}, &thinsp;</p>
                    <p v-show="vcard.address.postal_code != null, vcard.address.postal_code != '' ">{{vcard.address.postal_code}}</p>
                </div>
                <div class="detail-content-address" style="padding-bottom: 10px;">{{vcard.address.country}}</div>
            </div>
            <div v-show="vcard.note != null" class="select-option-single">
                <div class="detail-header">Notes</div>
                <div class="detail-content-note">{{vcard.note}}</div>
            </div>
        </liffgroup>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                vcard: {
                    image: 'https://www.w3schools.com/howto/img_avatar.png',
                    firstname: 'Jeroen',
                    lastname: 'Desloovere',
                    company: 'Siesqo',
                    note: 'Seeking for freelance work with over 10 years of graphic design experience. Feel free to get in touch!',
                    jobtitle: 'Web Developer',
                    role: 'Data Protection Officer',
                    email: 'info@jeroendesloovere.be',
                    phone: '+66 85-184-4422',
                    address: {
                        building_name: 'The Star Estate @ Rama 3',
                        building_floor: '18',
                        building_number: '',
                        moo: '',
                        soi: 'Rama 3 41',
                        road: 'Rama 3',
                        sub_district: 'Bangpongpang',
                        district: 'Yannawa',
                        province: 'Bangkok',
                        postal_code: '10120',
                        country: 'Thailand',
                    },
                    website: 'http://www.jeroendesloovere.be',
                },
            }
        },

        methods: {
            getVCard() {

            },
            generateVCard() {

            },
            clickMessage() {

            },
            clickCall() {

            },
            clickVideo() {

            },
            clickMail() {

            },

        },

        mounted() {
            this.getVcard;
        },
    }
</script>

<style scoped>
    body div {
        color: black;
        font-family: "Roboto", "Helvetica", "Arial", sans-serif;
    }

    p {
        margin: 0;
    }

    .row {
        margin: 0;
    }

    .profile {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        align: center;
    }

    .select-option {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .select-option-first {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
    }

    .select-option-single {
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .detail-content {
        padding: 5px 0 10px 0;
        color: #2C7AFE;
        font-size: 18px;
        font-weight: 400;
    }

    .detail-content-note {
        padding: 5px 0 10px 0;
        font-size: 18px;
        font-weight: 400;
    }

    .detail-content-address {
        font-size: 18px;
        font-weight: 400;
    }

    .detail-header {
        padding-top: 10px;
        font-weight: 400;
    }
</style>
