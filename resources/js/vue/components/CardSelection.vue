<template>
    <div style="margin-top: 20px;">

        <selector @click="selected = !selected" :label="label" :currentoption="currentOption"></selector>

        <liffdrawer @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="currentview" :closeable="true">
            <div v-if="selected">
                <div v-if="userCards.length != 0" style="padding-top: 20px;">
                    <div v-for="userCard in userCards" @click="select(userCard)"
                         style="width: 100vw; height: 50px; background-color: white; border: #DDDDDD 0.8px solid;">
                        <div class="row" style="margin-left: 20px;">
                            <div class="col-3" style="padding: 0;">
                                <img v-if="userCard.number.substr(0,1) == 5" style=" height: 35px; margin: 6.5px 0 0 0;" src="/icon/mastercard-01.svg">
                                <img v-else-if="userCard.number.substr(0,1) == 4" style=" height: 35px; margin: 6.5px 0 0 0;" src="/icon/visa.svg">
                                <img v-else style=" height: 35px; margin: 6.5px 0 0 0;" src="/icon/photo.svg">
                            </div>
                            <div class="col-7" style="padding: 0;">
                                <div style="height: 50px; display: table-cell; vertical-align: middle; ">

                                    <div style="display: table;">
                                        <p style="font-size: 16px; font-weight: 400; color: #444444; margin: 0;">
                                            {{userCard.number}}
                                        </p>
                                        <p style="font-size: 12px; font-weight: 300; color: #bbbbbb; margin: 2px 0 0 0;">
                                            Expiration Date {{userCard.expiration_month+'/'+userCard.expiration_year}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div @click.stop="removeCard(userCard.id, userCard.number)" class="col-2" style="padding: 0;">
                                <div style="height: 50px; display: table-cell; vertical-align: middle; ">
                                    <div style="display: table;">
                                        <i style="color: #cccccc;" class="material-icons">delete</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Add Card Button-->
                <div style="margin-top: 20px;">
                    <selector @click="newCard" label="Add Card"></selector>
                </div>

                <liffdrawer @close="showAddCard = false" align="right" :maskclosable="true" label="Add Card" currentview="Card" :closeable="true">
                    <div v-if="showAddCard === true">

                        <liffgroup>
                            <liffinput :value.sync="card.number" placeholder="Card number" @input="card.number = $event" type="mask"
                                       :mask="['#### #### #### ####']" maxlength="19"></liffinput>
                            <liffinput type="textUpperCase" :value.sync="card.holder_name" placeholder="Name on card" @input="card.holder_name = $event"></liffinput>
                            <liffinput :value.sync="value2" placeholder="Expiration date" type="picker2" @input="expiration = $event" :title="title2"
                                       :data="list2" delimiter="/"></liffinput>
                            <liffinput :value.sync="card.cvv" type="mask" maxlength="3" placeholder="Security code" @input="card.cvv = $event"
                                       :mask="['###']"></liffinput>

                        </liffgroup>

                        <liffbottombutton @confirm="addNewCard()" label="Save"/>
                    </div>
                </liffdrawer>
            </div>
        </liffdrawer>

    </div>
</template>
<script>
    export default {
        props: {
            label: {required: true},
            currentview: {default: 'Back'},
            isselect: {required: false},
            selectedoption: {require: false},
            disabled: {required: false},
            user: {required: true},
        },

        data() {
            return {
                showAddCard: false,
                // New Credit Card Data
                card: new Form({
                    user_id: null,
                    holder_name: null,
                    number: null,
                    expiration_month: null,
                    expiration_year: null,
                    cvv: null,
                }),

                expiration: null,
                currentOption: null,
                selected: false,
                userCards: null,

                //Card Option
                title2: '',
                list2: null,
                value2: ['Month', 'Year'],
            };
        },

        mounted() {
            this.getCard();
            this.getCardOption();
            this.selected = this.isselect;
            this.card.user_id = this.user;
        },
        methods: {
            getCard() {
                const user_id = this.user;
                return new Promise((resolve, reject) => {
                    axios.get('/api/creditcard', {
                        params: {
                            'user_id': user_id,
                        }
                    })
                        .then(response => {
                            this.userCards = response.data.data;
                            this.currentOption = response.data.data[0].number;
                            this.$emit('input', response.data.data[0]);
                            // console.log(response);
                            // console.log(response.data.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            // console.log(error);
                            reject(error.response);
                        });
                });
            },
            select(option) {
                this.currentOption = option.number;
                this.$emit('input', option);
                this.selected = false;
            },
            newCard() {
                this.showAddCard = !this.showAddCard;
            },
            addNewCard() {
                if (this.expiration === null) {
                    notify('The expiration date field is required', 'danger');
                }
                else if (this.expiration != null) {
                    this.card.expiration_month = this.expiration[0];
                    this.card.expiration_year = this.expiration[1];
                    this.saveCard();
                }
            },
            saveCard() {
                this.card.post('/api/creditcard', false)
                    .then(response => {
                        notify('success', 'success');
                        this.emptyCardInput();
                        this.getCard();
                        // console.log(response);
                    })
                    .catch(response => {
                        notify(response.message, 'danger');
                        // console.log(response);
                    });
            },
            removeCard(id, number) {
                //Deleted Notify

                //Delete data in back-end
                if (confirm('Do you want to delete this card?'))
                    this.card.delete('/api/creditcard/' + id)
                        .then(response => {
                            notify('Card ' + number + ' has been removed.', 'danger');
                            this.getCard();
                            // console.log(response);
                        })
                        .catch(response => {
                            notify('fail', 'danger');
                            // console.log(response);
                        })
            },
            getCardOption() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/creditcard/get/cardoption')
                        .then(response => {
                            this.list2 = response.data.data;
                            // console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            // console.log(error);
                            reject(error.response);
                        });
                });

            },
            emptyCardInput() {
                this.card.holder_name = null;
                this.card.number = null;
                this.card.expiration_month = null;
                this.card.expiration_year = null;
                this.card.cvv = null;
                this.expiration = null;
                this.showAddCard = false;
            },
        },
        computed: {}
    };
</script>
<style scoped>
    .input-theme {
        display: table;
        padding: 0 20px;
        height: 50px;
        width: 100vw;
        background-color: white;
        border: #DDDDDD 0.8px solid;
    }

    .input-theme-dark {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #3B3736;
    }

    .input-label {
        font-weight: 400;
        color: #5b5553;
    }

    .input-label-dark {
        font-weight: 400;
        color: #FFD25A;
    }

    .current-option {
        text-align: right;
        font-weight: 300;
        color: #929292;
        padding-right: 0;
    }

    .current-option-dark {
        text-align: right;
        font-weight: 300;
        color: #BBBBBB;
        padding-right: 0;
    }

    .next {
        text-align: left;
        padding-left: 10px;
        color: #DDDDDD;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .next-dark {
        text-align: left;
        padding-left: 10px;
        color: #777777;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .option-wrapper {
        margin-top: 20px;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .select-option {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .text-option {
        padding-left: 15px;
        text-align: left;
        font-weight: 400;
        color: #5b5553;
    }

    .checked {
        text-align: left;
        padding-left: 20px;
        color: #2D7AFF;
    }

    .disabled {
        color: #BBBBBB;
    }

    .card_save {
        font-family: Roboto, Helvetica, Arial, sans-serif;;
        font-size: 16px;
        font-weight: 500;
        height: 60px;
        width: 100vw;
        display: table-cell;
        vertical-align: middle;
        background-color: rgb(59, 55, 54);
        color: rgb(255, 210, 90);
    }

    .card_inputbox {
        font-size: 12px;
        border: none;
        border-top: 0.5px solid #f1f1f1;
        height: 50px;
        border-radius: 0;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 0 20px;
        line-height: 10px;
        color: #929292;
    }

    ::placeholder {
        color: #BBBBBB;
    }
</style>
