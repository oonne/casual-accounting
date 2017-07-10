<template>
    <div class="expenses-list">
        <ErrorBar :text="errorMsg" />
        <BottomNav active='expenses' />

        <ul>
            <li v-for="expenses in expensesList">
                {{expenses.expenses_item}}<br>
                {{expenses.expenses_money}}<br>
                {{expenses.expenses_date}}<br>
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
            expensesList: []
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
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

    .expenses-list {
        ul {
            padding: 10px;

            li {
                position: relative;
                width: 100%;
                height: 100px;
                margin-bottom: 10px;
                padding: 10px;
                background-color: #fff;
                border-radius: 4px;
                overflow: hidden;
                box-shadow: 0 0 1px #ccc;
                color: #666;
            }
        }
    }

</style>


