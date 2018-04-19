<?php

namespace Delz\XFYun;

/**
 * 语音听写
 * @package Delz\XFYun
 */
class IAT extends BaseApi
{
    /**
     * 接口网址
     */
    const API_IAT = 'http://api.xfyun.cn/v1/service/v1/iat';

    /**
     * 引擎类型，可选值：sms16k（16k采样率普通话音频）、sms8k（8k采样率普通话音频）等，其他参见引擎类型说明
     *
     * @var string
     */
    protected $engineType = 'sms16k';

    /**
     * 音频编码，可选值：raw（未压缩的pcm或wav格式）、speex（speex格式）、speex-wb（宽频speex格式）
     *
     * @var string
     */
    protected $aue = 'raw';

    /**
     * 音频数据，base64 编码后进行 urlencode，要求 base64 编码和 urlencode 后大小不超过2M，原始音频时长不超过60s
     *
     * 这里直接传入二进制，执行的时候程序会编码
     *
     * @var string
     */
    protected $audio;

    /**
     * {@inheritdoc}
     */
    function send()
    {
        if (!$this->getAudio()) {
            throw new \InvalidArgumentException('audio is not set.');
        }
        $headerParams = [
            'engine_type' => $this->getEngineType(),
            'aue' => $this->getAue()
        ];
        $bodyParams = [
            'audio' => base64_encode($this->getAudio())
        ];
        $response = $this->sendRequest(self::API_IAT, $headerParams, $bodyParams);
        $data = json_decode($response->getBody(), true);
        if ($data['code'] == '0') {
            return $data['data'];
        } else {
            throw new ApiException($data['code'], $data['desc']);
        }
    }

    /**
     * @return string
     */
    public function getEngineType()
    {
        return $this->engineType;
    }

    /**
     * @param string $engineType
     *
     * @return $this
     */
    public function setEngineType($engineType)
    {
        if (!in_array($engineType, ['sms16k', 'sms8k'])) {
            throw new \InvalidArgumentException(
                sprintf("engineType should be 'sms16k' or 'sms8k', %s given.", $engineType)
            );
        }
        $this->engineType = $engineType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAue()
    {
        return $this->aue;
    }

    /**
     * @param string $aue
     *
     * @return $this
     */
    public function setAue($aue)
    {
        if (!in_array($aue, ['raw', 'speex', 'speex-wb'])) {
            throw new \InvalidArgumentException(
                sprintf("engineType should be 'raw'  or 'speex' or 'speex-wb', %s given.", $aue)
            );
        }
        $this->aue = $aue;

        return $this;
    }

    /**
     * @return string
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param string $audio
     *
     * @return $this
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;

        return $this;
    }
}