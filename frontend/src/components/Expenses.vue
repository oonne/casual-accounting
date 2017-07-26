<template>
    <div class="expenses-list">
        <ErrorBar :text="errorMsg" />
        <BottomNav active='expenses' />

        <ul>
            <li v-for="(expenses, index) in expensesList" @click="editingOn(index)" :class="{ 'editing': (index==editingIndex) }">
                <div class="info">
                    <p class="item">{{expenses.expenses_item}}</p>
                    <p class="date_category">{{expenses.expenses_date}} {{getCategoryName(expenses.expenses_category)}}</p>
                </div>
                <div class="money" :class="'color-'+expenses.expenses_category">{{expenses.expenses_money}}</div>
                
                <div class="edit">
                    <div class="money_category_item" :class="'color-'+expenses.expenses_category">
                        <div class="money">
                            <input v-model.number="editingExpenses.expenses_money">
                        </div>
                        <div class="category" @click="changeCategory">
                            {{getCategoryName(expenses.expenses_category)}}
                        </div>
                        <div class="item">
                            <input v-model.trim="editingExpenses.expenses_item">
                        </div>
                    </div>
                    <div class="handler_date">
                        <div class="date">
                            <input type="date" v-model="editingExpenses.expenses_date">
                        </div>
                        <div class="handler" @click="changeHandler">
                            {{getHandlerName(expenses.expenses_handler)}}
                        </div>
                    </div>
                    <div class="expenses-btn-list">
                        <div class="expenses-btn-delete" @click="deleteExpenses">删除</div>
                        <div class="expenses-btn-save" :class="{ 'expenses-btn-disable': !editingExpenses.expenses_item }" @click="saveExpenses">保存</div>
                    </div>
                </div>
            </li>
        </ul>
        <LoadMore v-show="loading"/>        
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav';
import ErrorBar from './ErrorBar';
import LoadMore from './LoadMore';

export default {
    extends: Base,
    name: 'expenses',
    components: {
        'BottomNav': BottomNav,
        'ErrorBar': ErrorBar,
        'LoadMore': LoadMore,
    },
    data () {
        return {
            expensesList: [],
            categoryList: [],
            handlerList: [],
            editingIndex: null,
            editingExpenses: {
                'id': null,
                'expenses_date': null,
                'expenses_item': '',
                'expenses_money': 0,
                'expenses_category': null,
                'expenses_handler': null,
                'expenses_remark': '',
            },
        }
    },
    created: function () {
        this.getUser(this.init)
    },
    methods: {
        init: function () {
            let vm = this
            vm.getList()
            window.addEventListener('scroll', vm.handleScroll)
        },
        getList: function () {
            let vm = this;
            fetch('/api/expenses/index?page='+vm.currentPage, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Auth-Token': vm.token
                }
            })
            .then(function (response) {
                if (response.status == 200) {
                    return response.json()
                } else if (response.status == 401) {
                    vm.errorMsg = '未登录'
                    vm.noLog()
                } else {
                    vm.errorMsg = response.statusText
                }
            })
            .then(function (data) {
                vm.loading = false
                if (data) {
                    if (!data.Ret) {
                        vm.pageCount = data.Meta.pageCount
                        vm.currentPage = data.Meta.currentPage
                        vm.categoryList = data.Extra.category
                        vm.handlerList = data.Extra.handler
                        vm.expensesList = vm.expensesList.concat(data.Data)
                    } else {
                        vm.errorMsg = vm.getFirstAttr(data.Data.errors)
                        console.warn(data.Data.errors)
                    }
                }
            })
            .catch(function (error) {
                vm.loading = false
                console.error(error)
                vm.errorMsg = '服务器故障'
            })
        },
        handleScroll: function(){
            let vm = this;
            if(vm.checkScrollEnd() && !vm.loading){
                if(vm.pageCount>vm.currentPage){
                    vm.currentPage++;
                    vm.loading = true;
                    vm.getList();
                }
            };
        },
        getCategoryName: function (id) {
            let vm = this
            let name = ''
            for (let category of vm.categoryList) {
                if (category.id == id) {
                    name = category.category_name
                }
            }
            return name
        },
        getHandlerName: function (id) {
            let vm = this
            let name = ''
            for (let handler of vm.handlerList) {
                if (handler.id == id) {
                    name = handler.handler_name
                }
            }
            return name
        },
        editingOn: function(index){
            let vm = this
            if (vm.editingIndex === null) {
                let expenses = vm.expensesList[index]
                vm.editingIndex = index
                vm.editingExpenses = expenses
            }
        },
        changeCategory: function(){
            let vm = this
            if (vm.editingIndex != null) {
                let id = 0
                for (let i in vm.categoryList) {
                    if (vm.categoryList[i].id == vm.editingExpenses.expenses_category) {
                        if (parseInt(i)+1<vm.categoryList.length) {
                            id = vm.categoryList[parseInt(i)+1].id
                        } else {
                            id = vm.categoryList[0].id
                        }
                    }
                }
                vm.editingExpenses.expenses_category = id   
            }
        },
        changeHandler: function(){
            let vm = this
            if (vm.editingIndex != null) {
                let id = 0
                for (let i in vm.handlerList) {
                    if (vm.handlerList[i].id == vm.editingExpenses.expenses_handler) {
                        if (parseInt(i)+1<vm.handlerList.length) {
                            id = vm.handlerList[parseInt(i)+1].id
                        } else {
                            id = vm.handlerList[0].id
                        }
                    }
                }
                vm.editingExpenses.expenses_handler = id
            }
        },
        deleteExpenses: function() {
            alert('TODO');
        },
        saveExpenses: function() {
            let vm = this
            if (vm.editingIndex != null && vm.editingExpenses.expenses_item) {
                let expenses = JSON.stringify(vm.editingExpenses)
                vm.loading = true
                fetch('/api/expenses/update', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Auth-Token': vm.token
                    },
                    body: expenses
                })
                .then(function (response) {
                    if (response.status == 200) {
                        return response.json()
                    } else if (response.status == 401) {
                        vm.errorMsg = '未登录'
                        vm.noLog()
                    } else {
                        vm.errorMsg = response.statusText
                    }
                })
                .then(function (data) {
                    vm.loading = false
                    if (data) {
                        if (!data.Ret) {
                            vm.expensesList[vm.editingIndex] = vm.editingExpenses
                            vm.editingIndex = null
                        } else {
                            vm.errorMsg = vm.getFirstAttr(data.Data.errors)
                            console.warn(data.Data.errors)
                        }
                    }
                })
                .catch(function (error) {
                    vm.loading = false
                    console.error(error)
                    vm.errorMsg = '服务器故障'
                })
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

    .color-1{
        background-color: $colorA;
    }
    .color-2{
        background-color: $colorB;
    }
    .color-3{
        background-color: $colorC;
    }
    .color-4{
        background-color: $colorD;
    }

    $itemHeight: 64;
    $itemEditingHeight: 230;

    .expenses-list {
        ul {
            padding: 10px 10px #{($bottomNavHeight)+10}px 10px;
            
            li {
                @extend .card;
                position: relative;
                height: #{$itemHeight}px;
                transition: height .3s;
                margin-bottom: 10px;
                overflow: hidden;
                display: flex;

                .info {
                    flex: 1 1 100%;
                    height: #{$itemHeight}px;
                    padding: 10px;

                    .item {
                        color: #666;
                    }
                    .date_category {
                        color: #aaa;
                        font-size: 0.8rem;
                    }
                }

                .money {
                    flex: 0 0 80px;
                    width: 80px;
                    height: #{$itemHeight}px;
                    line-height: #{$itemHeight}px;
                    color: #fff;
                    text-align: center;
                }

                .edit {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    width: 100%;
                    height: #{$itemEditingHeight}px;
                    opacity: 0;
                    transition: opacity .3s;
                    background-color: #fff;

                    input {
                        border: none;
                        background:transparent;
                    }

                    .money_category_item{
                        height: 124px;
                        padding: 0 30px;
                        transition: background-color .3s;

                        .money {
                            width: 70%;
                            height: 64px;
                            float: left;
                            overflow: hidden;
                            
                            input {
                                color: #fff;
                                font-size: 2rem;   
                                line-height: 64px;
                            }
                        }
                        .category {
                            width: 30%;
                            float: right;
                            text-align: right;
                            color: #fff;
                            line-height: 64px;
                            font-size: 0.9rem;
                        }
                        .item {
                            clear: both;
                            width: 100%;
                            height: 60px;
                            border-top: 2px dashed #fff;

                            input {
                                width: 100%;
                                text-align: center;
                                color: #fff;   
                                font-size: 1.1rem;   
                                line-height: 60px;
                            }
                        }
                    }
                    .handler_date {
                        padding: 0 30px;
                        height: 56px;
                        position: relative;
                        display: flex;
                        justify-content: space-between;

                        .date {
                            flex: 1 1 50%;

                            input {
                                line-height: 56px;
                                border: none;
                                background:transparent;
                                color: #777;
                            }
                        }
                        .handler {
                            flex: 1 1 50%;
                            line-height: 56px;
                            color: #777;
                            text-align: right;
                        }
                    }
                    .handler_date:after {
                        @extend .line-bottom;
                    }

                    .expenses-btn-list {
                        width: 100%;
                        height: 50px;
                        display: flex;
                        justify-content: space-between;

                        .expenses-btn {
                            width: 50%;
                            height: 50px;
                            line-height: 50px;
                            text-align: center;
                        }
                        .expenses-btn-delete {
                            @extend .expenses-btn;
                            color: $dangerColor;
                        }
                        .expenses-btn-save {
                            @extend .expenses-btn;
                            position: relative;
                            color: #333;
                        }
                        .expenses-btn-save:after {
                            @extend .line-left;
                        }
                        .expenses-btn-disable {
                            color: #999;
                        }
                    }
                }
            }

            .editing {
                height: #{$itemEditingHeight}px;

                .edit {
                    opacity: 1;
                }
            }
        }
    }

</style>


