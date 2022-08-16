import request from '@/utils/request'

export function getSysInfo() {
    return request({
        url: `/api/public/systemInfo`,
        method: 'get'
    })
}
