# 科大讯飞 REST API PHP SDK

## 如何获取

    composer require delz/xfyun
    
## 参考文档地址

http://doc.xfyun.cn/rest_api/

## 调用语音合成接口


1. 实例化客户端对象

    $client = new Client("{APPID}","{APIKEY}");
    
2. 生成TTS对象，并发送请求

```
    try {   
        $content = $client->createTTS()->setText('您好')->send();    
        $file = fopen(__DIR__ . '/test.wav',"w");    
        fwrite($file, $content);   
        fclose($file);   
    } catch(ApiException $e) {   
        echo $e->getMessage();  
    }
```

## 语音听写接口

1. 实例化客户端对象

    $client = new Client("{APPID}","{APIKEY}");
    
2. 生成IAT对象，并发送请求

```
    try {
        $content = $client->createIAT()->setAudio(file_get_contents(__DIR__.'/test.wav'))->send();
        echo $content;
    } catch(ApiException $e) {
        echo $e->getMessage();
    }
``` 