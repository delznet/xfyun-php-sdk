<?php

namespace Delz\XFYun;

use GuzzleHttp\Client as HttpClient;

/**
 * @package Delz\XFYun
 */
class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * 应用Id
     *
     * @var string
     */
    protected $appId;

    /**
     * 接口密钥
     *
     * @var string
     */
    protected $apiKey;

    /**
     * @param $appId
     * @param $apiKey
     */
    public function __construct($appId, $apiKey)
    {
        $this->appId = $appId;
        $this->apiKey = $apiKey;
    }

    /**
     * 创建一个tts实例
     */
    public function createTTS()
    {
        return new TTS($this);
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient();
        }
        return $this->httpClient;
    }

}