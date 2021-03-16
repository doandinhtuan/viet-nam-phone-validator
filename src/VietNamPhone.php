<?php
namespace SkylineBird\PhoneValidator;

class VietNamPhone
{
    protected $prefixTenNumers = '086|096|097|098|088|090|093|089|091|094|092';
    protected $prefixElevenNumbers = '0162|0163|0164|0165|0166|0167|0168|0169|0120|0121|0122|0126|0128|0911|0941|0123|0124|0125|0127|0129|0188|0186|0993|0994|0996|0199|0995|0997';
    protected $errorType;

    const ERROR_TYPE_FORMAT = 1;
    const ERROR_TYPE_NUMERIC = 2;
    const ERROR_TYPE_LENGTH = 3;
    const NOT_ERROR = '';


    public function __construct(){}


    public function check($phoneNumber)
    {
        $phoneNumber = $this->removeCountryCode($phoneNumber);

        if (!preg_match('/^[0-9]+$/', $phoneNumber)) {
            $this->errorType = self::ERROR_TYPE_NUMERIC;
            return false;
        }

        $phoneLength = strlen($phoneNumber);
        if ($phoneLength < 10 || $phoneLength > 11) {
            $this->errorType = self::ERROR_TYPE_LENGTH;
            return false;
        }

        if ($phoneLength === 10 && !preg_match('/^'.$this->prefixTenNumers.'/', $phoneNumber)) {
            $this->errorType = self::ERROR_TYPE_FORMAT;
            return false;
        }

        if ($phoneLength === 11 && !preg_match('/^'.$this->prefixElevenNumbers.'/', $phoneNumber)) {
            $this->errorType = self::ERROR_TYPE_FORMAT;
            return false;
        }

        $this->errorType = self::NOT_ERROR;
        return true;
    }


    public function error()
    {
        switch ($this->errorType) {
            case self::ERROR_TYPE_NUMERIC:
                $msg = 'Điện thoại phải là chữ số';
                break;
            case self::ERROR_TYPE_LENGTH:
                $msg = 'Điện thoại phải có độ dài từ 10 hoặc 11 số';
                break;
            default:
                $msg = 'Đúng số điện thoại';
                break;
        }
        return $msg;
    }

    protected function removeCountryCode($phone)
    {
        $phone = preg_replace('/^\+84/', '', $phone);
        if (!preg_match('/^0/', $phone)) {
            $phone = '0'.$phone;
        }
        return $phone;
    }
}