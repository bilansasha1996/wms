<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <v-card-title>
                <div class="btn-panel-stock">
                    <div class="top-left-block">
                        <product :type_btn='"add"' @updateTable="getProducts"></product>
                    </div>
                    <div class="top-left-block">
                        <categoty></categoty>
                    </div>
                    <div class="top-left-block">
                        <v-chip color="primary" label outline>Остаток на складе на {{ balance.toFixed(2) }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
                    </div>

                </div>
                <v-spacer></v-spacer>
                <div class="col-md-7">
                    <v-text-field
                            append-icon="search"
                            label="Поиск..."
                            single-line
                            hide-details
                            v-model="search"
                    ></v-text-field>
                </div>
            </v-card-title>
        </div>
        <v-data-table
                v-bind:headers="tableHeaders"
                v-bind:items="tableItems"
                v-bind:pagination.sync="pagination"
                v-bind:no-results-text="noResultsText"
                :search="search"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">

                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.tm }}</td>
                <td class="text-xs-center">{{ props.item.units }}</td>
                <td class="text-xs-center">{{ props.item.total }}</td>
                <td class="text-xs-center">{{ props.item.all }}</td>
                <td class="text-xs-center">{{ props.item.price }}</td>
                <td class="btn-column text-xs-center">
                    <product :id="props.item.id" :type_btn='"edit"' @updateTable="getProducts"></product>
                </td>
                <td class="btn-column text-xs-center">
                    <v-tooltip top>
                        <v-btn color="red" slot="activator" v-on:click="deleteProduct(props.item.id)"> <i class="material-icons">close</i></v-btn>
                        <span>Удалить товар</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </div>
</template>

<script>
    import product from '../dialogs/Product.vue';
    import categoty from '../dialogs/Categoty.vue';

    export default {

        components: {
            product,
            categoty
        },

        data () {
            return {
                search: '',
                noResultsText: 'Такого товара нет',
                pagination: {rowsPerPage: 10, page: 1},
                tableHeaders: [],
                tableItems: [],
                balance:0
            }
        },
        created: function(){
            this.getProducts();
            this.getBalance();
            this.getCategories()
        },

        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {
            deleteProduct:function (id) {
                axios.post('/product/' + id, {
                    _method: 'DELETE',
                }).then(response => {
                    this.$store.dispatch('successBlock', {text:"Товар удалён", time:1000});
                    this.getProducts();
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },
            getBalance: function () {
                let vueObject = this;
                let uri = '/balance';
                Axios.get(uri).then((response) => {
                    let data = response.data;
                    vueObject.balance = data.balance;
//                    vueObject.salesAmount = data.salesAmount;
//                    vueObject.marriageAmount = data.marriageAmount;
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },

            getProducts:function () {
                let uri = '/product';
                Axios.get(uri).then((response) => {
                    this.tableHeaders    = response.data.headers;
                    this.tableItems      = response.data.items;
                });
            },
            getCategories:function () {
                this.$store.dispatch('getCategories');
            },
        }
    }
</script>
<style>
    .pagination__more{
        display: none;
    }
    tr .btn-column{
        width: 100px!important;
        margin:0!important;
        padding: 0!important;
    }
    .btn-panel-stock div{
        display: inline-block;
        margin-right: 5px;
    }
</style>