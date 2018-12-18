<?php
/**
 * Crypsic - A speed metal encryption library for php
 *
 * @category   Encryption And Password
 *
 * @author     Moviet
 * @license    MIT Public License
 *
 * @return     @Defaultconfiguration
 */
namespace Moviet\Heavy\Speed;

abstract class Verbal
{
    const MIN_BYTES_SIZE = 16;

    const MEDIUM_BYTES_SIZE = 24;

    const MAX_BYTES_SIZE = 32;

    const BYTES_CONVERSION = 48;

    const KEEP_BYTES_CLEAN = null;

    const CHBYTES = '8bit';

    const ZERO_BITS_FIXED = 0;

    const MIN_CIPHER_SIZE = 128;

    const MEDIUM_CIPHER_SIZE = 192;

    const MAX_CIPHER_SIZE = 256;

    const HMAC_GIVER = 'sha256';

    const DEFAULT_MODE = 'CBC-256';	

    const RAWKEY_ALGOS = 'sha512';

    const BLOCK_MODE = 
    [
        'CTR-128'  	=> 'AES-128-CTR',

        'CTR-192'  	=> 'AES-192-CTR',

        'CTR-256'  	=> 'AES-256-CTR',

        'CBC-128'  	=> 'AES-128-CBC',

        'CBC-192'  	=> 'AES-192-CBC',

        'CBC-256'  	=> 'AES-256-CBC'
    ];

    const DEFAULT_COST = 'cost';

    const DEFAULT_COST_LENGTH = 14;

    const MEMORY_KEY = 'memory_cost';

    const TIME_KEY = 'time_cost';

    const THREAD_KEY = 'threads';

    const OPTIONS_KEY = 'options';

    const DEFAULT_MEMORY_COST = 1666;

    const DEFAULT_TIME_COST = 6;

    const DEFAULT_THREAD_LENGTH = 6;

    /**
    * Generate password compatible suits
    * 
    * @param string $algorithm
    * @return string
    */
    public static function hashAlgo($algorithm)
    {
        switch ($algorithm) {
            case 'Default' :
            $algoName = PASSWORD_DEFAULT;
            break;

            case 'Argon2i' :
            $algoName = PASSWORD_ARGON2I;
            break;

            case 'Argon2d' :
            $algoName = PASSWORD_ARGON2D;
            break;

            case 'Argon2id' :
            $algoName = PASSWORD_ARGON2ID;
            break;

            default :
            break;
        }

        return $algoName;
    }
}
