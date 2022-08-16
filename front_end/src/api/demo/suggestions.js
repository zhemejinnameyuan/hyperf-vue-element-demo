import request from '@/utils/request'

/**
 * 搜索建议
 * @param data
 * @returns {AxiosPromise}
 */
export function getInputSuggestions(data) {
    return request({
        url: `/api/demo/elasticsearch/suggestions`,
        method: 'post',
        data
    })
}

