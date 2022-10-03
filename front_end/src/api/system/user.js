import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 用户-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getUser(data) {
    return request({
        url: `/api/system/user?` + jsonToUrlParams(data),
        method: 'get'
    })
}

/**
 * 用户-新增
 * @param data
 * @returns {AxiosPromise}
 */
export function addUser(data) {
    return request({
        url: '/api/system/user',
        method: 'post',
        data
    })
}

/**
 * 用户-更新
 * @param data
 * @returns {AxiosPromise}
 */
export function updateUser(data) {
    return request({
        url: '/api/system/user',
        method: 'put',
        data
    })
}

/**
 * 用户-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteUser(id) {
    return request({
        url: `/api/system/user?id=${id}`,
        method: 'delete'
    })
}

/**
 * 用户权限组-获取列表select option
 */
export function getUserGroupOptionDataList() {
    return request({
        url: `/api/system/authGroup/optionList`,
        method: 'get'
    })
}
