import request from '@/utils/request'

/**
 * 基金-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getFundDataList(data) {
    return request({
        url: `/api/demo/fund/fundDataList`,
        method: 'post',
        data
    })
}

/**
 * 基金-获取列表
 * @param fund_code
 * @returns {AxiosPromise}
 */
export function getFundCcmxList(fund_code) {
    return request({
        url: `/api/demo/fund/fundCcmx?fund_code=${fund_code}`,
        method: 'get',
    })
}

