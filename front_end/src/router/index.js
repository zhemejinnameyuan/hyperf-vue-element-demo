import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)


const _import = require('./import-' + process.env.NODE_ENV)

/* Layout */
import Layout from '@/layout'
import {getMenuList} from "@/api/router";

//全局路由(无需嵌套上左右整体布局)
const globalRoutes = [
  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true,
    name: 'Page404'
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'Login'
  },
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
        {path: '/redirect/:path(.*)', component: () => import('@/views/redirect/index')}
    ]
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'Dashboard', icon: 'dashboard', affix: true }
      }
    ]
  },
];

const router = new Router({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: globalRoutes
})



window.localStorage.setItem('storageMenu', '0')
router.beforeEach((to, from, next) => {
  if (fnCurrentRouteType(to, globalRoutes) === 'global') {
    next()
  } else {
    if (window.localStorage.getItem('storageMenu') === '0') {
      getMenuList().then(response => {
        if (response && response.code === 0) {
          window.localStorage.setItem('storageMenu', '1')
          fnAddDynamicMenuRoutes(response.data)
          next({ ...to, replace: true })
        }
      }).catch((e) => {
        console.log(`%c${e} 请求菜单列表和权限失败，跳转至登录页！！`, 'color:blue')
        router.push({name: 'login'})
      })
      next()
    }else{
      next()
    }
  }
})

/**
 * 判断当前路由类型, global: 全局路由, main: 主入口路由
 * @param {*} route 当前路由
 */
function fnCurrentRouteType (route, globalRoutes = []) {
  let temp = []
  for (let i = 0; i < globalRoutes.length; i++) {
    if (route.path === globalRoutes[i].path) {
      return 'global'
    } else if (globalRoutes[i].children && globalRoutes[i].children.length >= 1) {
      temp = temp.concat(globalRoutes[i].children)
    }
  }
  return temp.length >= 1 ? fnCurrentRouteType(route, temp) : 'main'
}

/**
 * 添加动态(菜单)路由
 * @param {*} menuList 菜单列表
 * @param {*} routes 递归创建的动态(菜单)路由
 */
function fnAddDynamicMenuRoutes(menuList = [], routes = []) {

  let newMenu = handleMenuComponent(menuList);
  //拼装404
  newMenu.concat( { path: '*', redirect: { name: '404' } });

  router.addRoutes(newMenu);
  router.options.routes = globalRoutes.concat(newMenu);
}

/**
 * 循环处理Component
 */
function handleMenuComponent(treeNodes) {
  let newMenu = [];

  for (let i = 0, len = treeNodes.length; i < len; i++) {
    let currentNode = treeNodes[i];
    let children = currentNode.children;

    if(currentNode['type'] == 1){
      //处理component
      try {
        let tmp_component = currentNode['component'];
        if (tmp_component == 'Layout') {
          currentNode['component'] = Layout
        } else {
          currentNode['component'] = _import(`${tmp_component}`)
        }
      } catch (e) {
        console.log(`import-component:${e}`)
      }

      newMenu = newMenu.concat(currentNode);
      if (children && children.length > 0) {
        handleMenuComponent(children);
      }
    }

  }
  return newMenu;
}


export default router
