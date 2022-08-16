import request from '@/utils/request'

/** ================== 站点分类 ================== */
/**
 * 分类-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getSiteTypeDataList(data) {
    return request({
        url: `/api/hotArticle/getSiteTypeDataList`,
        method: 'post',
        data
    })
}

/**
 * 分类-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function siteTypeSave(data) {
    return request({
        url: '/api/hotArticle/siteTypeSave',
        method: 'post',
        data
    })
}

/**
 * 分类-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function siteTypeDelete(id) {
    return request({
        url: `/api/hotArticle/siteTypeDelete?id=${id}`,
        method: 'post'
    })
}

/**
 * 分类-获取列表select option
 * @returns {AxiosPromise}
 */
export function getSiteTypeOptionDataList() {
    return request({
        url: `/api/hotArticle/siteTypeOptionDataList`,
        method: 'post'
    })
}


/** ================== 网站管理 ================== */

/**
 * 网站管理-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getSiteConfigDataList(data) {
    return request({
        url: `/api/hotArticle/getSiteConfigDataList`,
        method: 'post',
        data
    })
}

/**
 * 网站管理-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function siteConfigSave(data) {
    return request({
        url: '/api/hotArticle/siteConfigSave',
        method: 'post',
        data
    })
}

/**
 * 网站管理-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function siteConfigDelete(id) {
    return request({
        url: `/api/hotArticle/siteConfigDelete?id=${id}`,
        method: 'post'
    })
}

/**
 * 网站管理-手动执行
 * @param id
 * @returns {AxiosPromise}
 */
export function runSite(id) {
    return request({
        url: `/api/hotArticle/runSite?id=${id}`,
        method: 'post'
    })
}

