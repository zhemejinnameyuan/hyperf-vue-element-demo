<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
/**
 * 容器实例.
 */
function container(): object
{
    return \Hyperf\Utils\ApplicationContext::getContainer();
}

/**
 * 记录日志.
 * @param string $prefix
 */
function logger($prefix = 'hyperf'): object
{
    return container()->get(\Hyperf\Logger\LoggerFactory::class)->get($prefix);
}

/**
 * 返回成功
 * @param string $msg
 * @param array $data
 * @param int $code
 */
function response_success($msg = '操作成功', $data = [], $code = 0): object
{
    $responseData = [
        'message' => $msg,
        'data' => $data,
        'code' => $code,
    ];

    return container()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->json($responseData);
}

/**
 * 返回失败.
 * @param string $msg
 * @param array $data
 * @param int $code
 */
function response_error($msg = '操作失败', $data = [], $code = 1): object
{
    $responseData = [
        'message' => $msg,
        'data' => $data,
        'code' => $code,
    ];

    return container()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->json($responseData);
}

/**
 * 加强版 md5.
 * @param $str '要加密的字符串'
 * @param string $key 加密key
 */
function hyperf_md5($str, $key = 'hyperf'): string
{
    return $str === '' ? '' : md5(sha1($str) . $key);
}

/**
 * 获取客户端IP地址
 * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param bool $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false): string
{
    $server = container()->get(\Hyperf\HttpServer\Contract\RequestInterface::class)->getServerParams();

    $type = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }

    if ($adv) {
        if ($server['proxy_add_x_forwarded_for']) {
            $arr = explode(',', $server['proxy_add_x_forwarded_for']);
            $pos = array_search('unknown', $arr);
            if ($pos !== false) {
                unset($arr[$pos]);
            }
            $ip = trim($arr[0]);
        } elseif ($server['http_x_forwarded_for']) {
            $arr = explode(',', $server['http_x_forwarded_for']);
            $pos = array_search('unknown', $arr);
            if ($pos !== false) {
                unset($arr[$pos]);
            }

            $ip = trim($arr[0]);
        } elseif (isset($server['http_client_ip'])) {
            $ip = $server['http_client_ip'];
        } elseif (isset($server['remote_addr'])) {
            $ip = $server['remote_addr'];
        }
    } elseif (isset($server['remote_addr'])) {
        $ip = $server['remote_addr'];
    }
    // IP地址合法验证,兼容ipv6
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false) {
        $long = sprintf('%u', ip2long($ip));
        $ip = $long ? [$ip, $long] : [$ip, 0];
        return $ip[$type];
    }
    return '0.0.0.0';
}

/**
 * 验证Google验证码
 * @param $google_auth_key
 * @param $code
 */
function check_google_code($google_auth_key, $code): bool
{
    return make(\App\Utils\GoogleAuthenticator::class)->verifyCode($google_auth_key, $code);
}

/**
 * 生成Google密钥.
 */
function create_google_key(): string
{
    return make(\App\Utils\GoogleAuthenticator::class)->createSecret(16);
}

/**
 * 时间戳转换日期
 * @param int $time 时间戳
 * @return false|string
 */
function time2date($time = 0)
{
    if ($time) {
        return date('Y-m-d H:i:s', $time);
    }
    return '';
}

/**
 * config 设置.
 * @param $value
 * @return mixed
 */
function config_set(string $key, $value)
{
    $container = container();
    if (! $container->has(\Hyperf\Contract\ConfigInterface::class)) {
        throw new \RuntimeException('ConfigInterface is missing in container.');
    }
    return $container->get(\Hyperf\Contract\ConfigInterface::class)->set($key, $value);
}

/**
 * aes加密.
 * @param $data
 * @param string $secretKey
 * @return string
 */
function common_aes_encrypt($data, $secretKey = '')
{
    if (is_array($data)) {
        $data = json_encode($data);
    }
    if ($secretKey == '') {
        $secretKey = env('AES_KEY');
    }

    try {
        return bin2hex(openssl_encrypt($data, 'AES-128-CBC', $secretKey, OPENSSL_PKCS1_PADDING, $secretKey));
    } catch (Exception $exception) {
        return '';
    }
}

/**
 * aes 解密.
 * @param $data
 * @param string $secretKey
 * @return false|string
 */
function common_aes_decrypt($data, $secretKey = '')
{
    if ($secretKey == '') {
        $secretKey = env('AES_KEY');
    }

    try {
        return openssl_decrypt(hex2bin($data), 'AES-128-CBC', $secretKey, OPENSSL_PKCS1_PADDING, $secretKey);
    } catch (\Exception $exception) {
        return '';
    }
}

/**
 *  创建一个数组，用一个数组的值作为其键名，另一个数组的值作为其值
 * 基于array_combine （支持长度不一样拼接）.
 * @param mixed $keys
 * @param mixed $values
 * @return array
 */
function diy_array_combine($keys, $values)
{
    $lengthMax = count($keys);
    if ($lengthMax < count($values)) {
        $lengthMax = count($values);
    }

    $new_arr = [];
    for ($i = 0; $i < $lengthMax; ++$i) {
        $key = '';
        if (isset($numbers[$i])) {
            $key = $keys[$i];
        }

        $value = '';
        if (isset($letters[$i])) {
            $value = $values[$i];
        }

        $new_arr[$key] = $value;
    }
    return $new_arr;
}
