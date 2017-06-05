<template>
    <div class="login">
        <div class="logo">
            <svg class="icon logo-bg" aria-hidden="true"><use xlink:href="#icon-hexagon"></use></svg>
            <h1 class="logo-text">趣饮吧</h1>
        </div>
        <div class="form" :class="{shake: hasError }">
            <input v-model="username" placeholder="帐号">
            <input v-model="password" placeholder="密码">
            <button class="button" @click="login">登录</button>
            <div class="error-msg">{{errorMsg}}</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import base from './base';

export default {
    extends: base,
    name: 'login',
    data () {
        return {
            username: '',
            password: '',
            hasError: false,
            errorMsg: ''
        }
    },
    watch: {
        hasError: function (hasError) {
            var vm = this;
            if (vm.hasError) {
                setTimeout(function(){
                    vm.hasError = false;
                }, 1000);
            }
        }
    },
    methods: {
        login: function(){
            var vm = this;
            if ( !vm.username || !vm.password) {
                vm.hasError = true;
                vm.errorMsg = '请填写帐号和密码';
            } else {
                axios.post('/api/user/login', {username: vm.username, password: vm.password})
                    .then(function (response) {
                        if (response.status == 200) {
                            if (response.data.Ret) {
                                vm.hasError = true;
                                vm.errorMsg = vm.getFirstAttr(response.data.Data.errors);
                                console.warn(response.data.Data.errors);
                            } else {
                                localStorage.setItem('user', JSON.stringify(response.data.Data));
                                vm.$router.push('/');
                            }
                        } else {
                            vm.hasError = true;
                            vm.errorMsg = response.statusText;
                        }
                    })
                    .catch(function (error) {
                        vm.hasError = true;
                        vm.errorMsg = '服务器故障';
                        console.error(error);
                    });
            }
        }
    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
    $logoSize: 120; 
    
    .login {
        width: 100%;

        .logo {
            position: relative;
            margin: 18vh auto 80px;
            width: #{$logoSize}px;
            height: #{$logoSize}px;

            .logo-content{
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
            }

            .logo-text {
                @extend .logo-content;
                display: block;
                color: #fff;
                font-size: 1.6rem;
                line-height: #{$logoSize}px;
                text-align: center;
                font-weight: 400;
            }
            
            .logo-bg {
                @extend .logo-content;
                animation: rotation linear 30s infinite;
                color: #e7604a;
                background-image: linear-gradient(135deg, #e7604a, #de6262);
                -webkit-text-fill-color: transparent;
                -webkit-background-clip: text;
            }
            @keyframes rotation {
                from {
                    transform: rotate(0deg);
                    filter: hue-rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                    filter: hue-rotate(-360deg);
                }
            }
        }

        .form{
            width: 300px;
            margin: auto;

            input {
                display: block;
                width: 100%;
                height: 2.4rem;
                line-height: 2.4rem;
                margin: 20px 0;
                padding: 0 14px;
                border-radius: 4px;
                border: none;
                background-color: #ddd;
                color: #333;
            }
            button {
                display: block;
                width: 100%;
                height: 2.5rem;
                line-height: 2.5rem;
                margin: 20px 0;
                background-color: #5cb85c;
                border-color: #4cae4c;
                border-radius: 4px;
                border: none;
                text-align: center;
                color: #fff;
            }
            button:active {
                background-color: #4cae4c;
            }

            .error-msg {
                color: #a94442;
                text-align: center;
                font-style: 0.8rem;
            }
        }

        // error shake
        .shake {
            animation: shake linear .8s;
        }
        @keyframes shake {
            0% {
                transform: translateX(-8px);
            }
            20% {
                transform: translateX(10px);
            }
            40% {
                transform: translateX(-8px);
            }
            60% {
                transform: translateX(6px);
            }
            80% {
                transform: translateX(-4px);
            }
            100% {
                transform: translateX(0);
            }
        }

    }

</style>
