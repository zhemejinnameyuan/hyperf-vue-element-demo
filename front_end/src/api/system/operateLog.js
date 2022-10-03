import request from '@/utils/request'
import {jsonToUrlParams} from "@/utils";

/**
 * 操作日志-获取列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getOperateLog(data) {
    return request({
        url: `/api/system/operateLog?` + jsonToUrlParams(data),
        method: 'get'
    })
}

/**
 * 操作日志-获取业务类型
 * @param data
 * @returns {AxiosPromise}
 */
export function getOperateBusinessType(data) {
    return request({
        url: `/api/system/operateLog/businessType`,
        method: 'get',
        data
    })
}
