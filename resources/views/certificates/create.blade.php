<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>PDF</title>
</head>
<body>
<div id="app">

    <img src="https://drive.google.com/uc?export=view&id=1Q285IrQXo4WMbGsIEbgMfnhO34HG_Z5r" alt="" style="width: 297mm; height: 210mm">
    <div ref="formName" :style="{'left': nameLeft + 'px'}" style="position: absolute; top:275px; font: 700 40px Kanit; color: black;">@{{ certificate.sex + certificate.name }}</div>
    <div ref="formCourse" :style="{'left': courseLeft + 'px'}" style="position: absolute; top:405px; font: 400 24px Kanit; color: #494949;">"@{{ certificate.course }}"</div>
    <div ref="formGivenDate" :style="{'left': givenDateLeft + 'px'}" style="position: absolute; top:495px; font: 400 24px Kanit; color: #494949;">
        ให้ไว้ ณ วันที่ @{{ certificate.givenDate }}
    </div>
    <div ref="formLecturerName" :style="{'left': lecturerNameLeft + 'px'}" style="position: absolute; top:630px; font: 400 24px Kanit; color: #494949;">
        (@{{ certificate.lecturerName }})
    </div>
    <div ref="lecturerPosition" :style="{'left': lecturerPositionLeft + 'px'}" style="position: absolute; top:660px; font: 400 24px Kanit; color: #494949;">
        @{{ certificate.lecturerPosition }}
    </div>
    <div :style="{'left': (689 + 123) + 'px'}" style="position: absolute; top:690px; font: 400 24px Kanit; color: #494949;">
        วิทยากร
    </div>
    <div class="non-print-css" style="width: 297mm; text-align: center; padding-top: 10px">
        <button @click="print" style="width: 100px; font: 18px Kanit; background-color: #c2000b; color: white; ">Save</button>
    </div>
</div>
</body>
<script>
    new Vue({
        el: '#app',
        data: {
            certificate,
            nameLeft: null,
            courseLeft: null,
            givenDateLeft: null,
            lecturerNameLeft: null,
            lecturerPositionLeft: null,
        },
        methods: {
            print() {
                window.print();
            }
        },
        created() {
        },
        mounted() {
            this.nameLeft = (1122.52 - this.$refs.formName.clientWidth) / 2;
            this.courseLeft = (1122.52 - this.$refs.formCourse.clientWidth) / 2;
            this.givenDateLeft = (1122.52 - this.$refs.formGivenDate.clientWidth) / 2;
            this.lecturerNameLeft = (689 + (320 - this.$refs.formLecturerName.clientWidth) / 2);
            this.lecturerPositionLeft = (689 + (320 - this.$refs.lecturerPosition.clientWidth) / 2);
        },
    })
</script>
<style>
    body {
        background-color: #595959;
    }

    @media print {
        @page {
            margin: 0;
            size: 297mm 210.1mm;
        }

        .non-print-css {
            display: none;
        }
    }
</style>
</html>
