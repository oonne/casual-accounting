import Vue from 'vue'
import App from './App'
import router from './router'

Vue.config.productionTip = false

new Vue({
	el: '#app',
	router,
	template: '<App/>',
	components: { App },
	beforeCreate : function () {
		let user = localStorage.getItem('user');
		if (user) {
			router.push('/');
		} else {
			console.warn('未登录');
			router.push('login');
		}
	}
})

// router.beforeEach((to, from, next) => {
// 	if (to.name != 'Login') {
// 		let user = localStorage.getItem('user');
// 		if (user) {
// 			next();
// 		} else {
// 			console.warn('未登录');
// 			router.push('login');
// 		}
// 	} else {
// 		next();
// 	}
// })
