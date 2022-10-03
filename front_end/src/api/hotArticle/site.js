import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";


/** ================== 网站管理 ================== */

/**
 * 网站管理-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getSite(data) {
    return request({
        url: `/api/hotArticle/site?` + jsonToUrlParams(data),
        method: 'get'
    })
}

/**
 * 网站管理-添加
 * @param data
 * @returns {AxiosPromise}
 */
export function addSite(data) {
    return request({
        url: '/api/hotArticle/site',
        method: 'post',
        data
    })
}

/**
 * 网站管理-修改
 * @param data
 * @returns {AxiosPromise}
 */
export function updateSite(data) {
    return request({
        url: '/api/hotArticle/site',
        method: 'put',
        data
    })
}

/**
 * 网站管理-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteSite(id) {
    return request({
        url: `/api/hotArticle/site?id=${id}`,
        method: 'delete'
    })
}

/**
 * 网站管理-手动执行
 * @param id
 * @returns {AxiosPromise}
 */
export function runSite(id) {
    return request({
        url: `/api/hotArticle/site/runSite?id=${id}`,
        method: 'post'
    })
}

