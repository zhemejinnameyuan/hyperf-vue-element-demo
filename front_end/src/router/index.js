import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)


const _import = require('./import-' + process.env.NODE_ENV)

/* Layout */
import Layout from '@/layout'
import {getMenuList} from "@/api/router";
/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

//全局路由(无需嵌套上左右整体布局)
const globalRoutes = [
  {path: '/404', component: () => import('@/views/404'),hidden: true},
  {path: '/login', component: () => import('@/views/login/index'),  hidden: true}
];

const router = new Router({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: globalRoutes
})




router.beforeEach((to, from, next) => {
  if (router.options.isAddDynamicMenuRoutes || fnCurrentRouteType(to, globalRoutes) === 'global') {
    next()
  } else {
    getMenuList().then(response => {
      if (response && response.code === 0) {
        fnAddDynamicMenuRoutes(response.data)
        next({ ...to, replace: true })
      }
    }).catch((e) => {
      console.log(`%c${e} 请求菜单列表和权限失败，跳转至登录页！！`, 'color:blue')
      router.push({ name: 'login' })
    })
    next()
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
