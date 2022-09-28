import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import {getToken, removeToken} from '@/utils/auth'

axios.defaults.retry = 1;
axios.defaults.retryDelay = 1000;

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 1000, // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent

    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['Authorization'] = '  Bearer ' + getToken()
    }
    return config
  },
  error => {
    // do something with request error
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data

    // if the custom code is not 0, it is judged as an error.
    if (res.code > 0) {
      //token失效 移除token 重新进入登录页面
      if(res.code == 401){
        // Message({
        //   message: "token失效，请重新登录",
        //   type: 'error',
        //   duration: 5 * 1000
        // })
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
