import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 菜单-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getMenu(data) {
    return request({
        url: `/api/system/menu?` + jsonToUrlParams(data),
        method: 'get',
    })
}

/**
 * 菜单-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function addMenu(data) {
    return request({
        url: '/api/system/menu',
        method: 'post',
        data
    })
}

/**
 * 菜单-更新
 * @param data
 * @returns {AxiosPromise}
 */
export function updateMenu(data) {
    return request({
        url: '/api/system/menu',
        method: 'put',
        data
    })
}

/**
 * 菜单-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteMenu(id) {
    return request({
        url: `/api/system/menu?id=${id}`,
        method: 'delete'
    })
}
