import request from '@/utils/request'

/**
 * 获取菜单列表
 * @param params
 * @returns {AxiosPromise}
 */
export function getMenuList(params) {
  return request({
    url: '/api/user/getMenuList',
    method: 'get',
    params
  })
}
