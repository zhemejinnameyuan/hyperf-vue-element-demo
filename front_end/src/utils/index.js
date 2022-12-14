import {MessageBox} from 'element-ui'
import store from "@/store";

/**
 * 合并json
 * @param json1
 * @param json2
 * @returns {any}
 */
export function mergeJson(json1, json2) {
    let length1 = 0, length2 = 0, jsonStr, str;
    for (let ever in json1) {
        length1++;
    }
    for (let ever in json2) {
        length2++;
    }
    if (length1 && length2) {
        str = ',';
    } else {
        str = '';
    }

    jsonStr = ((JSON.stringify(json1)).replace(/,}/, '}') + (JSON.stringify(json2)).replace(/,}/, '}')).replace(/}{/, str);
    return JSON.parse(jsonStr);
}

/**
 * 封装 confirm 确认框
 * @param title
 * @param callbackFunction
 */
export function confirmRequest(title = '确定要操作吗?', callbackFunction) {
    MessageBox.confirm(title, '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(callbackFunction).catch(err => {
        console.info(err)
    })
}

/**
 * json 转换为 url 参数
 * @param json
 * @returns {string}
 */
export  function jsonToUrlParams(json){
    return Object.keys(json).map(function (key) {
        return encodeURIComponent(key) + "=" + encodeURIComponent(json[key]);
    }).join("&");
}

/**
 * 判断是否有按钮级权限
 */
export function hasPermission(key) {
    const list = store.getters.buttonPermission
    // const list = ['system:menu:add','system:menu:edit','system:menu:delete'];
    return list.indexOf(key) !== -1
}
