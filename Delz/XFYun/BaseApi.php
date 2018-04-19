<?php

namespace Delz\XFYun;

use Psr\Http\Message\ResponseInterface;

/**
 * 所有接口请求的基类
 *
 * @package Delz\XFYun
 */
abstract class BaseApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 发送请求
     */
    abstract function send();

    /**
     * 发送请求
     *
     * @param string $url 接口网址
     * @param array $headerParams header参数
     * @param array $bodyParams body参数
     * @return ResponseInterface
     */
    protected function sendRequest($url, $headerParams = [], $bodyParams = [])
    {
        $headers = [
            'X-Appid' => $this->client->getAppId(),
            'X-CurTime' => time(),
            'X-Param' => base64_encode(json_encode($headerParams))
        ];
        $headers['X-CheckSum'] = md5($this->client->getApiKey() . $headers['X-CurTime'] . $headers['X-Param']);
        return $this->client->getHttpClient()->post($url, ['form_params' => $bodyParams, 'headers' => $headers]);
    }
}