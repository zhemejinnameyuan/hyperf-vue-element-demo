import request from '@/utils/request'

/**
 * 抽奖
 * @returns {AxiosPromise}
 */
export function submitLottery() {
    return request({
        url: `/api/demo/lottery/index`,
        method: 'post',
    })
}

