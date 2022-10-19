import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

import '@/styles/index.scss' // global css

import App from './App'
import store from './store'
import router from './router'

import '@/icons' // icon
import '@/permission' // permission control

//转盘抽奖
import lottery from 'vue-lottery'
import {hasPermission} from "@/utils";
Vue.use(lottery);

Vue.use(ElementUI);


// 注册一个全局自定义指令 `v-permission`
Vue.directive('permission', {
  inserted: (el, binding) => {
    const permission = binding.value;
    const result = hasPermission(permission);
    if (!result) {
      el.parentNode && el.parentNode.removeChild(el);
    }
  }
});

Vue.config.productionTip = false;

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});
