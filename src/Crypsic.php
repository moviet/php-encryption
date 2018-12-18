<?php
/**
 * Crypsic - A Speed Metal Encryption Library For PHP
 *
 * @category   Encryption
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Moviet\Heavy;

use \RuntimeException;
use Moviet\Heavy\CrypsicKey;
use Moviet\Heavy\Speed\Verbal;
use Moviet\Heavy\Exceptions\EqualsException;
use Moviet\Heavy\Exceptions\DecryptException;

/**
 * Let's Simplify
 */
class Crypsic
{
    /**
    * @param string key
    */
    protected static $key;

    /**
    * @param string key
    */
    protected static $has;

    /**
    * @param string password
    */
    protected static $auth;

    /**
    * @param string hash
    */
    protected static $hash;

    /**
    * @param string cipher
    */
    protected static $mode;

    /**
    * Check requirements
    * 
    * @throws RunTimeException; 
    */
    public function __construct()
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('Unable to load openssl extension');
        }

        if (!extension_loaded('mbstring')) {
            throw new RuntimeException('Unable to load mbstring extension');
        }

        if (!function_exists('hash_equals')) {
            throw new RuntimeException('Unable to load comparation hash function');
        }
    }

    /**
    * Generate key from any storage
    * and use a key to validate
    * 
    * @param string $key
    */
    public static function key($key)
    {
        self::$has = self::h2bin($key);
    }

    /**
    * Generate mac and build a nice salt
    * this is use to authenticated crypto
    * 
    * @return string
    */
    protected static function suitSalt()
    {
        return hash_hmac(
            Verbal::RAWKEY_ALGOS, self::$has . self::getMode(), self::$has
        );
    }

    /**
    * Load password from any request
    * 
    * @param string $password
    */
    public static function authKey($password)
    {
        self::$auth = (string) $password;

        return new self;
    }

    /**
    * Load data password from any storage
    * 
    * @param string $datahash
    */
    public function hash($datahash)
    {
        self::$hash = (string) $datahash;

        return $this;
    }

    /**
    * You encoded, you responsible to decoded
    * just make it readable yayy
    *  
    * @param string $string
    */
    public static function listen($string)
    {
        return base64_encode(self::cipherBlock($string));
    }

    /**
    * Just simply decoded the ciphertext
    * 
    * @param string $string
    */
    public static function look($string)
    {
        return self::record(base64_decode($string));
    }

    /**
    * Check what the operation modes
    * 
    * @return string
    */
    protected static function combatKey()
    {
        return self::suitSalt();
    }

    /**
    * Check parameter key if they use password
    * then verify the absolete if not then
    * we can authentify next
    * 
    * @throws Moviet\Heavy\Exceptions\EqualsException
    * @throws Moviet\Heavy\Exceptions\EqualsException
    * @return bool
    */
    protected static function verify()
    {
        if (!is_null(self::$hash)) {
            if (!password_verify(self::$auth, self::$hash)) {
                throw new EqualsException('Password does not match');
            } 
        }
    }

    /**
    * Compact the string, cipher modes, a key and ivector
    * and just make a zig-zag onto natively
    * 
    * @param string
    * @param mixed
    * @return mixed
    */
    protected static function crypto($string, $nonce)
    {
        return openssl_encrypt(
            $string, self::getMode(), self::getKey(), OPENSSL_RAW_DATA, $nonce
        );
    }

    /**
    * Generate and compact all the chunks here
    * and deliver the raw crypto
    * 
    * @return mixed
    */
    protected static function cipherBlock($string)
    {
        $nonce = self::nonceCash();
        
        return $nonce . self::hmac($string, $nonce) . self::crypto($string, $nonce);
    }

    /**
    * Generate mac, salt and bandage the raw crypto
    * so we can get assosiated data
    *  
    * @param string
    * @param mixed
    * @return string
    */
    protected static function hmac($string, $nonce)
    {
        return hash_hmac(
            Verbal::HMAC_GIVER, self::crypto($string, $nonce), self::combatKey(), true
        );
    }

    /**
    * Generate initial vector for spesific cipher
    * 
    * @return mixed
    */
    protected static function nonceCash()
    {
        if (!function_exists('random_bytes')) {
            return openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::getMode()));

        } else {
            return random_bytes(openssl_cipher_iv_length(self::getMode()));
        }
    }

    /**
    * Now we check the mac for something called 'noin bullet'  
    * then check what the key that was generated
    * if they use a password so we must proof it
    * and compare the spesific length
    * 
    * @param string $string
    * @throws Moviet\Heavy\Exceptions\EqualsException
    * @throws Moviet\Heavy\Exceptions\DecryptException
    * @return mixed
    */
    protected static function record($string)
    {		
        if (!hash_equals(self::compare($string), self::screw($string))) {
            throw new EqualsException('You have invalid data');
        }

        if (self::verify() !== false) {
            return openssl_decrypt(
                self::round($string), self::getMode(), self::$has, OPENSSL_RAW_DATA, self::rotate($string)
            );

        } else {
            throw new DecryptException("You can not decrypt invalid data");
        }
    }

    /**
    * Here the bytes crypto must not be invalid
    * 
    * @return mixed
    */
    protected static function rotate($string)
    {
        return mb_substr(
            $string, Verbal::ZERO_BITS_FIXED, Verbal::MIN_BYTES_SIZE, Verbal::CHBYTES
        );
    }

    /**
    * Screw up the bytes size to compare
    * 
    * @return mixed
    */
    protected static function screw($string)
    {
        return mb_substr(
            $string, Verbal::MIN_BYTES_SIZE, Verbal::MAX_BYTES_SIZE, Verbal::CHBYTES
        );
    }

    /**
    * Round up the crypto in spesific length
    * 
    * @return mixed
    */
    protected static function round($string)
    {
        return mb_substr(
            $string, Verbal::BYTES_CONVERSION, Verbal::KEEP_BYTES_CLEAN, Verbal::CHBYTES
        );
    }

    /**
    * Now we host an assosiated salt
    * to authenticate the data
    * 
    * @return mixed
    */
    protected static function compare($string)
    {
        return hash_hmac(
            Verbal::HMAC_GIVER, self::round($string), self::combatKey(), true
        );
    }

    /**
    * Calculate key for acceptable cipher mode
    * 
    * @param string $key
    * @return int
    */
    protected static function guideKey($key)
    {
        $length = mb_strlen($key, Verbal::CHBYTES);

        if ($length <=> self::calcKey() && self::calcKey() == Verbal::MIN_CIPHER_SIZE) {
            $total = Verbal::MIN_BYTES_SIZE;

        } elseif ($length <=> self::calcKey() && self::calcKey() == Verbal::MEDIUM_CIPHER_SIZE) {
            $total = Verbal::MEDIUM_BYTES_SIZE;

        } else {
            $total = Verbal::MAX_BYTES_SIZE;
        }

        return $total;
    }

    /**
    * Set cooperative cipher mode
    * 
    * @param string $cipher
    */
    public static function mode($cipher)
    {
        self::$mode = Verbal::BLOCK_MODE[$cipher];
    }

    /**
    * Get cipher mode, if nothing, set to default mode
    * 
    * @return string
    */
    protected static function getMode()
    {
        if (isset(self::$mode)) {
            $setmode = self::$mode;

        } else {
            $setmode = Verbal::BLOCK_MODE[Verbal::DEFAULT_MODE];
        }			

        return $setmode;
    }

    /**
    * Checking cipher operation mode
    * 
    * @return int
    */
    protected static function calcKey()
    {
        return preg_replace('/[^0-9]/','', self::getMode());
    }

    /**
    * We must generate a readable key
    * 
    * @param string $length
    * @return string
    */
    protected static function b2hex($length)
    {
        return bin2hex($length);
    }

    /**
    * We must reverse the key to generate cipher
    * and get the requirement length
    * 
    * @param string $length
    * @return string
    */
    protected static function h2bin($length)
    {
        return hex2bin($length);
    }

    /**
    * Generate random key with minimum requirement
    * if a length does look badass, says 4 bytes
    * we prevent with calculation and make it readable
    * 
    * @param string $key
    * @return string
    */
    public static function saveKey($key)
    {
        if (!function_exists('random_bytes')) {
            self::$key = self::b2hex(openssl_random_pseudo_bytes(self::guideKey($key)));

        } else {
            self::$key = self::b2hex(random_bytes(self::guideKey($key)));
        }

        return self::$key;
    }

    /**
    * Get key to generate crypto
    * 
    * @return mixed
    */
    protected static function getKey()
    {
        return self::h2bin(self::$key);
    }
}
