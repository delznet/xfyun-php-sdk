<?php

namespace Delz\XFYun;

/**
 * 语音合成
 * @package Delz\XFYun
 */
class TTS extends BaseApi
{
    /**
     * 语音合成接口
     */
    const API_TTS = 'http://api.xfyun.cn/v1/service/v1/tts';

    /**
     * 音频采样率，可选值：audio/L16;rate=8000，audio/L16;rate=16000
     *
     * @var string
     */
    protected $auf = 'audio/L16;rate=16000';

    /**
     * 音频编码，可选值：raw（未压缩的pcm或wav格式），lame（mp3格式）
     *
     * @var string
     */
    protected $aue = 'raw';

    /**
     * 发音人，可选值：详见http://www.xfyun.cn/services/online_tts
     *
     * @var string
     */
    protected $voiceName = 'xiaoyan';

    /**
     * 语速，可选值：[0-100]，默认为50
     *
     * @var string
     */
    protected $speed = '50';

    /**
     * 音量，可选值：[0-100]，默认为50
     *
     * @var string
     */
    protected $volume = '50';

    /**
     * 音高，可选值：[0-100]，默认为50
     *
     * @var string
     */
    protected $pitch = '50';

    /**
     * 引擎类型，可选值：aisound（普通效果），intp65（中文），intp65_en（英文），mtts（小语种，需配合小语种发音人使用），x（优化效果），默认为inpt65
     *
     * @var string
     */
    protected $engineType = 'intp65';

    /**
     * 文本类型，可选值：text（普通格式文本），默认为text
     *
     * @var string
     */
    protected $textType = 'text';

    /**
     * 待合成文本，使用utf-8编码，需urlencode，长度小于1000字节
     *
     * @var string
     */
    protected $text;

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        if (!$this->getText()) {
            throw new \InvalidArgumentException('text is not set.');
        }
        $headerParams = [
            'auf' => $this->getAuf(),
            'aue' => $this->getAue(),
            'voice_name' => $this->getVoiceName(),
            'speed' => $this->getSpeed(),
            'volume' => $this->getVolume(),
            'pitch' => $this->getPitch(),
            'engine_type' => $this->getEngineType(),
            'text_type' => $this->getTextType()
        ];
        //看开发文档需要urlencode，实际测试不需要
        //$this->setText(urlencode($this->getText()));
        $bodyParams = [
            'text' => $this->getText()
        ];
        $response = $this->sendRequest(self::API_TTS, $headerParams, $bodyParams);
        //根据Content-type判断是否成功
        //成功audio/mpeg，响应 body 为音频数据，可写入文件保存，保存类型由入参的 aue 决定
        //失败text/plain，响应 body 为 json 字符串
        $contextType = $response->getHeader('Content-Type');
        if ($contextType[0] == 'text/plain') {
            $errBody = json_decode($response->getBody(), true);
            throw new ApiException($errBody['desc'], $errBody['code']);
        } elseif ($contextType[0] == 'audio/mpeg') {
            return $response->getBody();
        } else {
            throw new ApiException('unknown error');
        }
    }

    /**
     * @return string
     */
    public function getAuf()
    {
        return $this->auf;
    }

    /**
     * @param string $auf
     *
     * @return $this
     */
    public function setAuf($auf)
    {
        if (!in_array($auf, ['audio/L16;rate=16000', 'audio/L16;rate=8000'])) {
            throw new \InvalidArgumentException(
                sprintf("auf should be 'audio/L16;rate=16000' or 'audio/L16;rate=8000', %s given.", $auf)
            );
        }
        $this->auf = $auf;

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
        if (!in_array($aue, ['raw', 'lame'])) {
            throw new \InvalidArgumentException(
                sprintf("aue should be 'raw' or 'lame', %s given.", $aue)
            );
        }
        $this->aue = $aue;

        return $this;
    }

    /**
     * @return string
     */
    public function getVoiceName()
    {
        return $this->voiceName;
    }

    /**
     * @param string $voiceName
     *
     * @return $this
     */
    public function setVoiceName($voiceName)
    {
        $this->voiceName = $voiceName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param string $speed
     *
     * @return $this
     */
    public function setSpeed($speed)
    {
        if ($speed < 0 || $speed > 100) {
            throw new \InvalidArgumentException(
                sprintf("speed should be greater than 0 and litter than 100, %s given.", $speed)
            );
        }
        $this->speed = (string)$speed;

        return $this;
    }

    /**
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param string $volume
     *
     * @return $this
     */
    public function setVolume($volume)
    {
        if ($volume < 0 || $volume > 100) {
            throw new \InvalidArgumentException(
                sprintf("volume should be greater than 0 and litter than 100, %s given.", $volume)
            );
        }
        $this->volume = (string)$volume;

        return $this;
    }

    /**
     * @return string
     */
    public function getPitch()
    {
        return $this->pitch;
    }

    /**
     * @param string $pitch
     *
     * @return $this
     */
    public function setPitch($pitch)
    {
        if ($pitch < 0 || $pitch > 100) {
            throw new \InvalidArgumentException(
                sprintf("pitch should be greater than 0 and litter than 100, %s given.", $pitch)
            );
        }
        $this->pitch = (string)$pitch;

        return $this;
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
        if (!in_array($engineType, ['aisound', 'intp65', 'intp65_en', 'mtts', 'x'])) {
            throw new \InvalidArgumentException(
                sprintf("engineType should be 'aisound' 、'intp65'、'intp65_en'、'mtts or 'x', %s given.", $engineType)
            );
        }
        $this->engineType = $engineType;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextType()
    {
        return $this->textType;
    }

    /**
     * @param string $textType
     *
     * @return $this
     */
    public function setTextType($textType)
    {
        if (!in_array($textType, ['text'])) {
            throw new \InvalidArgumentException(
                sprintf("textType should be 'text', %s given.", $textType)
            );
        }
        $this->textType = $textType;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;

    }

}