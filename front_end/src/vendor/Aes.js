import CryptoJS from 'crypto-js'
let KEY = CryptoJS.enc.Utf8.parse('abcdefghijklmnop')
let IV = CryptoJS.enc.Utf8.parse('abcdefghijklmnop')
// 加密
export function Encrypt (word, keyStr, ivStr) {
    let key = KEY
    let iv = IV

    if (keyStr) {
        key = CryptoJS.enc.Utf8.parse(keyStr)
        iv = CryptoJS.enc.Utf8.parse(ivStr)
    }
    let srcs = CryptoJS.enc.Utf8.parse(word)
    let encrypted = CryptoJS.AES.encrypt(srcs, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    // return encrypted.toString()
    return encrypted.ciphertext.toString().toUpperCase()
}
// 解密
export function Decrypt (word, keyStr, ivStr) {
    let key = KEY
    let iv = IV

    if (keyStr) {
        key = CryptoJS.enc.Utf8.parse(keyStr)
        iv = CryptoJS.enc.Utf8.parse(ivStr)
    }
    let encryptedHexStr = CryptoJS.enc.Hex.parse(word)
    let srcs = CryptoJS.enc.Base64.stringify(encryptedHexStr)
    let decrypt = CryptoJS.AES.decrypt(srcs, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })

    let decryptedStr = decrypt.toString(CryptoJS.enc.Utf8)
    return decryptedStr.toString()
}

