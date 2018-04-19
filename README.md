# 科大讯飞 REST API PHP SDK

## 调用语音合成接口


1. 实例化客户端对象

    $client = new Client("{APPID}","{APIKEY}");
    
2. 生成TTS对象，并发送请求

    try {   
        $content = $client->createTTS()->setText('您好')->send();    
        $file = fopen(__DIR__ . '/test.wav',"w");    
        fwrite($file, $content);   
        fclose($file);   
    } catch(ApiException $e) {   
        echo $e->getMessage();  
    }
