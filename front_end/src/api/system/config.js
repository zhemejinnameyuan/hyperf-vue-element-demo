import request from '@/utils/request'

/**
 * 配置-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getConfigDataList(data) {
    return request({
        url: `/api/system/configDataList`,
        method: 'post',
        data
    })
}

/**
 * 配置-保存
 * @param data
 * @returns {AxiosPromise}
 */
export function configSave(data) {
    return request({
        url: '/api/system/configSave',
        method: 'post',
        data
    })
}

/**
 * 配置-删除
 * @param id
 * @returns {AxiosPromise}
 */
export function configDelete(id) {
    return request({
        url: `/api/system/configDelete?id=${id}`,
        method: 'post'
    })
}
