<template>
    <v-layout row justify-right>
        <v-btn v-if="disabledModal" depressed  large disabled><i class="material-icons">add</i></v-btn>
        <v-btn v-else color="primary" large dark v-on:click="getProduct"><i class="material-icons">add</i></v-btn>
        <v-dialog v-model="dialog" persistent max-width="700px">
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>{{ product.name }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container fluid>
                        <v-layout row>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>В наличии</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-subheader v-if="showDataProduct">{{ product.total }}</v-subheader>
                                            <v-subheader v-else>0</v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs2>
                            </v-flex>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>Цена</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-subheader v-if="showDataProduct">{{ product.price + " " }} <span>  грн.</span></v-subheader>
                                            <v-subheader v-else><span>0 грн.</span></v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                        <v-layout row>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>Количество</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-text-field
                                                    id="test"
                                                    v-model.number="number"
                                                    v-on:blur="updateTotalPrice"
                                                    type="number"
                                                    required
                                                    :disabled="!showDataProduct"
                                                    autofocus
                                            ></v-text-field>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs2>
                            </v-flex>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>На</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>

                                            <v-subheader v-if="showDataProduct">{{ totalPrice + " " }} <span>  грн.</span></v-subheader>
                                            <v-subheader v-else><span>0 грн.</span></v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="closeDialog">Закрыть</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveData">Добавить в корзину</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    export default {
        props: ['id', 'disabledModal'],
        data: () => ({
            col: 1,
            dialog: false,
            number: 0,
            total: 0,
            product: {
                name: '',
                price: 0
            },
            totalPrice: 0,
            showDataProduct: false
        }),
        methods: {
            /**
             * Закрываем диалоговое окно
             */
            closeDialog: function() {
                this.showDataProduct = false;
                this.dialog = false;
            },

            /**
             * Обновляем общую стоимость за товар
             */
            updateTotalPrice: function() {
                if(this.modRound(this.number, 2)  >  this.modRound(this.total, 2)){
                    this.number = this.modRound(this.total, 2);
                    this.$store.dispatch('errorBlock', {text:"В наличии столько нет", time:1000});

                }

                if(this.modRound(this.number, 2) < 0){
                    this.number = 0;
                    this.$store.dispatch('errorBlock', {text:"Не может быть отрицательным", time:1000});

                }

                this.totalPrice = this.modRound(this.number * this.product.price, 2);
                this.product.total = this.modRound(this.total - this.number, 2);
            },

            /**
             * Сохраняем позицию в корзине
             */
            saveData: function () {
                if(this.modRound(this.number, 2) === 0){
                    this.$store.dispatch('errorBlock', {text:"Выберите минимальное количество", time:1000});

                    return false;
                }

                this.$store.dispatch('addProduct', {
                    product: this.product,
                    number: this.modRound(this.number, 2)
                });

                this.$store.state.basket.quantity++;
                this.$store.dispatch('successBlock', {text:"Товар добавлен в корзину", time:1000});


                this.closeDialog();
            },

            /**
             * Получаем товар по id и проверяем на наличие его в корзине
             */
            getProduct: function() {
                this.dialog = true;
                this.number = 0;
                let uri = '/product/'+this.id;
                Axios.get(uri).then((response) => {

                    let id = this.id;
                    let inBasket = 0;
                    let products = this.$store.getters.getProducts;
                    $(products).each(function (key, value) {
                        if(id === value.product.id){
                            inBasket = inBasket + value.number;
                        }
                    });

                    this.product = response.data.product;
                    this.product.total = this.modRound(this.product.total - inBasket, 2);
                    this.total = this.product.total;
                    this.totalPrice = 0;
                    this.showDataProduct = true;
                });
            },

            /**
             * Функция округления числа
             */
             modRound: function(value, precision){
                let precision_number = Math.pow(10, precision);
                return Math.round(value * precision_number) / precision_number;
            }
        }
    }
</script>
