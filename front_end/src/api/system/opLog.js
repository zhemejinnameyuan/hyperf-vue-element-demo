import request from '@/utils/request'

/**
 * 操作日志-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getOpLogDataList(data) {
    return request({
        url: `/api/user/opLogDataList`,
        method: 'post',
        data
    })
}

/**
 * 操作日志-获取业务类型
 * @param data
 * @returns {AxiosPromise}
 */
export function getOpBusinessTypeDataList(data) {
    return request({
        url: `/api/user/opBusinessTypeDataList`,
        method: 'post',
        data
    })
}
