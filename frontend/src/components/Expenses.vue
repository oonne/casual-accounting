<template>
    <div class="expenses">
        <ErrorBar :text="errorMsg" />
        <BottomNav active='expenses' />

        <input v-model="message">
        {{ message }}
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav';
import ErrorBar from './ErrorBar';

export default {
    extends: Base,
    name: 'expenses',
    components: {
        'BottomNav': BottomNav,
        'ErrorBar': ErrorBar,
    },
    data () {
        return {
            message: 'TODO-expenses'
        }
    },
    created: function () {
        let vm = this
        this.getUser(function(){
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
                if (data) {
                    if (!data.Ret) {
                        console.log(data);
                    } else {
                        vm.errorMsg = vm.getFirstAttr(data.Data.errors)
                        console.warn(data.Data.errors)
                    }
                }
            })
            .catch(function (error) {
                console.error(error)
                vm.errorMsg = '服务器故障'
            })
        })
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

</style>


