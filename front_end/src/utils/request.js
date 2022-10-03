import axios from 'axios'
import {  Message } from 'element-ui'
import store from '@/store'
import {getToken, removeToken} from '@/utils/auth'

axios.defaults.retry = 1;
axios.defaults.retryDelay = 1000;

const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API,
  timeout: 10000,
})

//请求栏截器
service.interceptors.request.use(
  config => {
    if (store.getters.token) {
      config.headers['Authorization'] = '  Bearer ' + getToken()
    }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// 响应拦截器
service.interceptors.response.use(
  response => {
    const res = response.data

    if (res.code > 0) {
      if(res.code == 401){
        removeToken();
        setTimeout(function () {
          location.reload()
        },500)
        return ;
      }

      Message({
        message: res.message || 'Error',
        type: 'error',
        duration: 5 * 1000
      })

      return Promise.reject(new Error(res.message || 'Error'))
    } else {
      return res
    }
  },
  error => {
    Message({
      message: "网络错误",
      type: 'error',
      duration: 3 * 1000
    })
    return false
  }
)

export default service
