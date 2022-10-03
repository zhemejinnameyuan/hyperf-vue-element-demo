import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 配置-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getConfig(data) {
    return request({
        url: `/api/system/config?` + jsonToUrlParams(data),
        method: 'get',
        data
    })
}

/**
 * 配置-新增
 * @param data
 * @returns {AxiosPromise}
 */
export function addConfig(data) {
    return request({
        url: '/api/system/config',
        method: 'post',
        data
    })
}

/**
 * 配置-更新
 * @param data
 * @returns {AxiosPromise}
 */
export function updateConfig(data) {
    return request({
        url: '/api/system/config',
        method: 'put',
        data
    })
}

/**
 * 配置-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteConfig(id) {
    return request({
        url: `/api/system/config?id=${id}`,
        method: 'delete'
    })
}
