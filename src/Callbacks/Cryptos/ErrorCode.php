<?php


namespace MuCTS\DingTalk\Callbacks\Cryptos;


class ErrorCode
{
    public static $OK = 0;

    public static $IllegalAesKey = 900004;
    public static $ValidateSignatureError = 900005;
    public static $ComputeSignatureError = 900006;
    public static $EncryptAESError = 900007;
    public static $DecryptAESError = 900008;
    public static $ValidateSuiteKeyError = 900010;
    public static $IllegalAccessToken = 40014;
}