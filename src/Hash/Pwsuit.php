<?php
/**
 * Pwsuit - A Password Decoration Library For PHP
 *
 * @category   Password Hash
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Moviet\Heavy\Hash;

use \RuntimeException;
use Moviet\Heavy\Speed\Verbal;
use Moviet\Heavy\Exceptions\EqualsException;

class Pwsuit
{
    /**
     * @param int $cost
     */
    protected static $cost;

    /**
     * @param int $memory
     */
    protected static $memory;

    /**
     * @param int $time
     */
    protected static $time;

    /**
     * @param int $thread
     */
    protected static $thread;

    /**
     * Set default
     */
    const DEFAULT = 'Default';

    /**
     * Require minimum PHP 5.6+
     * 
     * @throws RuntimeException 
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            throw new RuntimeException('You must upgrade your PHP version >= 5.6.0');
        }
    }

    /**
     * Create cost length
     * 
     * if it doesn't set by default a cost will set to 14
     * do not set under native default by php
     * native default by php set to 10
     * 
     * @param int $length
     * @return array
     * 
     */
    public static function cost(int $length)
    {
        static::$cost[Verbal::DEFAULT_COST] = $length;

        return new static;
    }

    /**
     * Create memory_cost length
     * 
     * This is optional by default memory_cost will set to 1666 
     * do not set under native default by php
     * native default by php set to 1024
     * 
     * @param int $length
     * @return array
     * 
     */
    public static function memory(int $length)
    {
        static::$memory[Verbal::MEMORY_KEY] = $length;

        return new static;
    }

    /**
     * Create time_cost length
     * 
     * This is optional by default time_cost will set to 6 
     * do not set under native default by php
     * native default by php set to 2
     * 
     * @param int $length
     * @return array
     */
    public function time(int $length)
    {
        static::$time[Verbal::TIME_KEY] = $length;

        return $this;
    }

    /**
     * Create threads length
     * 
     * This is optional by default threads will set to 6
     * do not set under native default by php
     * native default by php set to 2
     * 
     * @param int $length
     * @return array
     */
    public function thread(int $length)
    {
        static::$thread[Verbal::THREAD_KEY] = $length;

        return $this;
    }

    /**
     * Check attributes operation
     * 
     * @return array 
     */
    protected static function getLength()
    {
        return isset(static::$cost) ? static::$cost : static::costLength();				
    }

    /**
     * Check attributes for eg. password Argon
     * 
     * @return array
     */
    protected static function getOptions()
    {
        return isset(static::$memory) ? static::hashel() : static::options();
    }

    /**
     * Generate default values if attributes exist
     * eg. Password Argon
     * 
     * @return array
     * 
     */
    protected static function hashel()
    {
        return array_merge(static::$memory, static::$time, static::$thread);
    }

    /**
     * Check the current attributes that use for compatible version
     * if doesn't set will return to default config 
     * 
     * @param string $hashmode
     * @param string $key
     * @return string
     */
    public static function pwHash($hashmode, $key)
    {		
        $options = ($hashmode !== self::DEFAULT && version_compare(PHP_VERSION, '7.2.0', '>=')) ? static::getOptions() : static::getLength();

        return password_hash($key, Verbal::hashAlgo($hashmode), $options);
    }	

    /**
     * Create new hashed with the old hashed password
     * and check for compatible php version
     * if match will produce new hashed
     * 
     * @param string $hashmode
     * @param string $password
     * @param string $datahash
     * @return Moviet\Heavy\Exceptions\EqualsException
     */
    public static function pwRehash($hashmode, $password, $datahash)
    {
        $options = ($hashmode !== self::DEFAULT && version_compare(PHP_VERSION, '7.2.0', '>=')) ? static::getOptions() : static::getLength();

        if (password_verify($password, $datahash)) {
            if (password_needs_rehash($datahash, Verbal::hashAlgo($hashmode), $options)) {
                return static::pwhash($hashmode, $password);
            }

        } else {
            throw new EqualsException("Your password invalid");
        }
    }

    /**
     * Generate default cost length
     * 
     * @return array
     */
    protected static function costLength()
    {
        return [Verbal::DEFAULT_COST => Verbal::DEFAULT_COST_LENGTH];
    }

    /**
     * Generate default memory, time, threads for password Argon
     *  
     * @return array
     */
    protected static function options()
    {
        return [Verbal::MEMORY_KEY => Verbal::DEFAULT_MEMORY_COST, Verbal::TIME_KEY => Verbal::DEFAULT_TIME_COST, 
        Verbal::THREAD_KEY => Verbal::DEFAULT_THREAD_LENGTH];
    }

    /**
     * Generate password verification
     *  
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function pwTrust($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate password information
     *  
     * @param string $hash
     * @return array
     */
    public static function pwInfo($hash)
    {
        return password_get_info($hash);
    }
}
