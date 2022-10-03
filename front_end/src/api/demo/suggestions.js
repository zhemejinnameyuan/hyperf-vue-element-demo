import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 搜索建议
 * @param data
 * @returns {AxiosPromise}
 */
export function getInputSuggestions(data) {
    return request({
        url: '/api/demo/elasticsearch/suggestions?' + jsonToUrlParams(data),
        method: 'get'
    })
}

