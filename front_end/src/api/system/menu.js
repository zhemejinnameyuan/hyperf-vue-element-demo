import request from '@/utils/request'

/**
 * 菜单-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getMenuDataList(data) {
    return request({
        url: `/api/system/menuDataList`,
        method: 'post',
        data
    })
}

/**
 * 菜单-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function menuSave(data) {
    return request({
        url: '/api/system/menuSave',
        method: 'post',
        data
    })
}

/**
 * 菜单-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function menuDelete(id) {
    return request({
        url: `/api/system/menuDelete?id=${id}`,
        method: 'post'
    })
}
