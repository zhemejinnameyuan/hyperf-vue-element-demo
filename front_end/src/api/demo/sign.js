import request from '@/utils/request'

/**
 * 签到
 * @returns {AxiosPromise}
 */
export function doSign() {
    return request({
        url: `/api/demo/sign/index`,
        method: 'post',
    })
}

/**
 * 获取已签到天数
 * @returns {AxiosPromise}
 */
export function getSignDays() {
    return request({
        url: `/api/demo/sign/getSignDays`,
        method: 'post',
    })
}

