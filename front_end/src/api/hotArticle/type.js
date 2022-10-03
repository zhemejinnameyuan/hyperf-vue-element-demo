import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 分类-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getType(data) {
    return request({
        url: `/api/hotArticle/type?`+jsonToUrlParams(data),
        method: 'get'
    })
}

/**
 * 分类-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function addType(data) {
    return request({
        url: '/api/hotArticle/type',
        method: 'post',
        data
    })
}

/**
 * 分类-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function updateType(data) {
    return request({
        url: '/api/hotArticle/type',
        method: 'put',
        data
    })
}

/**
 * 分类-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteType(id) {
    return request({
        url: `/api/hotArticle/siteTypeDelete?id=${id}`,
        method: 'delete'
    })
}

/**
 * 分类-获取列表select option
 * @returns {AxiosPromise}
 */
export function getOptionList() {
    return request({
        url: `/api/hotArticle/type/optionList`,
        method: 'get'
    })
}
