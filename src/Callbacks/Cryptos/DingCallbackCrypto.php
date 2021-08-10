<?php

namespace MuCTS\DingTalk\Callbacks\Cryptos;

use MuCTS\DingTalk\Exceptions\Exception;

class DingCallbackCrypto
{
    /**
     * @var string 钉钉开放平台上，开发者设置的token
     */
    private $token;
    /**
     * @var string 钉钉开放台上，开发者设置的EncodingAESKey
     */
    private $encodingAesKey;
    /**
     * @var string  企业自建应用-事件订阅, 使用appKey
     *              企业自建应用-注册回调地址, 使用corpId
     *              第三方企业应用, 使用suiteKey
     */
    private $corpId;


    /**
     * 注意这里修改为构造函数
     *
     * DingCallbackCrypto constructor.
     * @param $token
     * @param $encodingAesKey
     * @param $ownerKey
     */
    function __construct($token, $encodingAesKey, $ownerKey)
    {
        $this->token          = $token;
        $this->encodingAesKey = $encodingAesKey;
        $this->corpId         = $ownerKey;
    }

    /**
     * @param $plain
     * @return array
     * @throws Exception
     */
    public function getEncryptedMap($plain):array
    {
        $timeStamp = time();
        $pc        = new Prpcrypt($this->encodingAesKey);
        $nonce     = $pc->getRandomStr();
        return $this->getEncryptedMapDetail($plain, $timeStamp, $nonce);
    }

    /**
     * 加密回调信息
     *
     * @param $plain
     * @param $timeStamp
     * @param $nonce
     * @return array
     * @throws Exception
     */
    public function getEncryptedMapDetail($plain, $timeStamp, $nonce):array
    {
        $pc = new Prpcrypt($this->encodingAesKey);

        $array = $pc->encrypt($plain, $this->corpId);
        $ret   = $array[0];
        if ($ret != 0) {
            throw new Exception('AES加密错误', ErrorCode::$EncryptAESError);
        }

        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $encrypt = $array[1];

        $array = get_sha1($this->token, $timeStamp, $nonce, $encrypt);
        $ret   = $array[0];
        if ($ret != 0) {
            throw new Exception('ComputeSignatureError', ErrorCode::$ComputeSignatureError);
        }
        $signature = $array[1];

        return [
            "msg_signature" => $signature,
            "encrypt"       => $encrypt,
            "timeStamp"     => $timeStamp,
            "nonce"         => $nonce
        ];
    }

    /**
     * 解密回调信息
     *
     * @param $signature
     * @param null $timeStamp
     * @param $nonce
     * @param $encrypt
     * @return mixed
     * @throws Exception
     */
    public function getDecryptMsg($signature, $nonce, $encrypt, $timeStamp = null)
    {
        if (strlen($this->encodingAesKey) != 43) {
            throw new Exception('IllegalAesKey', ErrorCode::$IllegalAesKey);
        }

        $pc = new Prpcrypt($this->encodingAesKey);

        if ($timeStamp == null) {
            $timeStamp = time();
        }

        $array = get_sha1($this->token, $timeStamp, $nonce, $encrypt);
        $ret   = $array[0];

        if ($ret != 0) {
            throw new Exception('ComputeSignatureError', ErrorCode::$ComputeSignatureError);
        }

        $verifySignature = $array[1];
        if ($verifySignature != $signature) {
            throw new Exception('ValidateSignatureError', ErrorCode::$ValidateSignatureError);
        }

        $result = $pc->decrypt($encrypt, $this->corpId);

        if ($result[0] != 0) {
            throw new Exception('DecryptAESError', ErrorCode::$DecryptAESError);
        }
        return $result[1];
    }
}
