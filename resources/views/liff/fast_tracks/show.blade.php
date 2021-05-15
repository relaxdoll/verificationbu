<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ getenv('APP_NAME') }}</title>
</head>
<body>
<div id="asset">
</div>
@include('footer')

<script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
<script src=" {{ mix('/js/vue/liffshow.js') }}"></script>

<script>

    new Vue({
        el: '#asset',

        data: {
            loaded: false,
            messageType,
            imagePath
        },

        watch: {
            loaded() {
                this.send();
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
                    liffId: '1653723693-qaKe3a6A'
                }).then(function (response) {
                    this.loaded = true;
                }.bind(this))
            },
            send() {
                switch (this.messageType) {
                    case 'view':
                        this.sendImageMap();
                        break;
                    case 'save':
                        this.sendImageSave();
                        break;
                }
                liff.closeWindow();
            },
            sendImageMap() {
                liff.sendMessages([{
                    "type": "flex", "altText": "QR Information", "contents": {
                        "type": "carousel", "contents": [
                            {
                                "type": "bubble",
                                "size": "giga",
                                "body": {
                                    "type": "box",
                                    "layout": "vertical",
                                    "contents": [
                                        {
                                            "type": "image",
                                            "url": this.imagePath,
                                            "size": "full"
                                        }
                                    ],
                                    "paddingAll": "0px"
                                }
                            }
                        ]
                    }
                }])
            },
            sendImageSave(){
                liff.sendMessages([{
                    "type": "image",
                    "originalContentUrl": this.imagePath,
                    "previewImageUrl": this.imagePath
                }])
            }
        },
    });
</script>
</body>
</html>






