import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 权限分组-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getAuthGroup(data) {
    return request({
        url: `/api/system/authGroup?` + jsonToUrlParams(data),
        method: 'get'
    })
}

/**
 * 权限分组-新增
 * @param data
 * @returns {AxiosPromise}
 */
export function addAuthGroup(data) {
    return request({
        url: '/api/system/authGroup',
        method: 'post',
        data
    })
}

/**
 * 权限分组-更新
 * @param data
 * @returns {AxiosPromise}
 */
export function updateAuthGroup(data) {
    return request({
        url: '/api/system/authGroup',
        method: 'put',
        data
    })
}

/**
 * 权限分组-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteAuthGroup(id) {
    return request({
        url: `/api/system/authGroup?id=${id}`,
        method: 'delete'
    })
}

/**
 * 获取菜单节点
 * @returns {AxiosPromise}
 */
export function getMenuTree() {
    return request({
        url: '/api/system/authGroup/menuTree',
        method: 'get'
    })
}

/**
 * 刷新节点
 * @returns {AxiosPromise}
 */
export function refreshNode() {
    return request({
        url: '/api/system/authGroup/refreshNode',
        method: 'post'
    })
}
