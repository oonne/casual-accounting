<template>
    <div class="income-list">
        <ErrorBar :text="errorMsg" />
        <BottomNav active='income' />

        <ul>
            <li v-for="income in incomeList">
                <div class="info">
                    <p class="item">{{income.income_item}}</p>
                    <p class="date_handler">{{income.income_date}} {{income.handler}}</p>
                </div>
                <div class="money" :class="'color-'+income.income_handler">{{income.income_money}}</div>
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
    name: 'income',
    components: {
        'BottomNav': BottomNav,
        'ErrorBar': ErrorBar,
        'LoadMore': LoadMore,
    },
    data () {
        return {
            incomeList: []
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
            fetch('/api/income/index?page='+vm.currentPage, {
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
                        vm.incomeList = vm.incomeList.concat(data.Data)
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
    .income-list {
        ul {
            padding: 10px 10px #{($bottomNavHeight)+10}px 10px;

            li {
                @extend .card;
                height: #{$itemHeight}px;
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
                    .date_handler {
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
            }
        }
    }

</style>


