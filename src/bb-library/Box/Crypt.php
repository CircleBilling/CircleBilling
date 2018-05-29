<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

/**
 * @see https://paragonie.com/blog/2015/05/if-you-re-typing-word-mcrypt-into-your-code-you-re-doing-it-wrong
 * Class Box_Crypt
 */
class Box_Crypt implements \Box\InjectionAwareInterface
{
    protected $di = NULL;

    const METHOD = 'aes-256-cbc';

    public function __construct()
    {
        if (!extension_loaded('mcrypt')) {
            throw new Box_Exception('php mcrypt extension must be enabled on your server');
        }
    }

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    public function encrypt($message, $pass = null)
    {
        $key = $this->_getSalt($pass);

        //if (mb_strlen($key, '8bit') !== 32) {
        //    throw new Exception("Needs a 256-bit key!");
        //}

        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivsize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $ciphertext);
    }

    public function decrypt($message, $pass = null)
    {
        if (is_null($message)){
            return false;
        }
        $key = $this->_getSalt($pass);

        $message = base64_decode($message);


        //if (mb_strlen($key, '8bit') !== 32) {
        //    throw new Exception("Needs a 256-bit key!");
        //}

        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($message, 0, $ivsize, '8bit');
        $ciphertext = mb_substr($message, $ivsize, null, '8bit');

        $result = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $result = trim($result);

        return $result;
    }

    private function _getSalt($pass = null)
    {
        if (null == $pass) {
            $pass = $this->di['config']['salt'];
        }
        return pack('H*', hash('md5', $pass));
    }
}