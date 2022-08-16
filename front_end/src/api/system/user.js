import request from '@/utils/request'

/**
 * 用户-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getUserDataList(data) {
    return request({
        url: `/api/user/userDataList`,
        method: 'post',
        data
    })
}

/**
 * 用户-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function userSave(data) {
    return request({
        url: '/api/user/userSave',
        method: 'post',
        data
    })
}

/**
 * 用户-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function userDelete(id) {
    return request({
        url: `/api/user/userDelete?id=${id}`,
        method: 'post'
    })
}

/**
 * 用户权限组-获取列表select option
 * @returns {AxiosPromise}
 */
export function getUserGroupOptionDataList() {
    return request({
        url: `/api/user/userGroupOptionDataList`,
        method: 'post'
    })
}
