import request from '@/utils/request'

export function getMenuList(params) {
  return request({
    url: '/api/user/getMenuList',
    method: 'get',
    params
  })
}
