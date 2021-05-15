<template>
    <card style="padding-top: 15px; border-radius: 5px;">
        <div style="padding: 10px;">
            <div class="row" v-if="header">
                <div class="col-12 text-center">{{header}}</div>
            </div>
            <div class="row" style="font-size: 14px;">
                <div class="col-6">
                    {{title}}
                </div>
                <div class="col-6 text-right">
                    {{side_title}}
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <strong style="font-weight: bold; font-size: 20px;">{{subject}}</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-12" style=" font-size: 12px; color: #b3b3b3">
                    {{content}}
                </div>
            </div>

            <div class="row" v-if="action">
                <div class="col-12">
                    <slot></slot>
                </div>
            </div>
        </div>
    </card>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'liff-maintenance-approval-request-list',
        props: {
            header: {
                type: String,
                required: false
            },
            title: {
                type: String,
                required: false
            },
            side_title: {
                type: String,
                required: false
            },
            subject: {
                type: String,
                required: false
            },
            content: {
                type: String,
                required: false
            },
            action: {
                type: Boolean,
                required: false
            },
        },
        data() {
            return {}
        },
        created() {
        },
        computed: {
            localComputed() {
                return true
            },
            ...mapState([
                'theme',
                'rowSelected',
            ])
        }
    }
</script>
