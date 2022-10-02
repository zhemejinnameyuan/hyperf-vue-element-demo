import request from '@/utils/request'

/**
 * 权限分组-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getUserGroupDataList(data) {
    return request({
        url: `/api/user/userGroupDataList`,
        method: 'post',
        data
    })
}

/**
 * 权限分组-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function userGroupSave(data) {
    return request({
        url: '/api/user/userGroupSave',
        method: 'post',
        data
    })
}

/**
 * 权限分组-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function userGroupDelete(id) {
    return request({
        url: `/api/user/userGroupDelete?id=${id}`,
        method: 'post'
    })
}

/**
 * 获取菜单节点
 * @returns {AxiosPromise}
 */
export function getMenuTree() {
    return request({
        url: '/api/user/menuTree',
        method: 'get'
    })
}

/**
 * 刷新节点
 * @returns {AxiosPromise}
 */
export function refreshNode() {
    return request({
        url: '/api/user/refreshNode',
        method: 'get'
    })
}
