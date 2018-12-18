<?php
/**
* Functional Testing
* Using PHP v7.2
*
* PHPUnit v7.5
*/
namespace Moviet\Testing;

use Moviet\Heavy\Crypsic;
use PHPUnit\Framework\TestCase;

class EncryptTest extends TestCase
{
    public function testProofRandomKey()
    {
        $randomKeyOne = crypsic::saveKey('This is my key');
        $randomKeyTwo = crypsic::saveKey('This is my key');

        $this->assertNotEquals($randomKeyOne, $randomKeyTwo);
    }

    public function testKeyCannotBeDumpAndMustBeSecret()
    {
        $key = crypsic::key('8957aa5d61ecbba69ec35acd69ba06ec');

        $this->assertNull($key);
    }

    public function testAuthKeyWithAnyValue()
    {
        $pass = crypsic::authKey('string');

        if (is_object($pass)) {
            $value = false;
        }

        $this->assertFalse($value);
    }

    public function testHashWithAnyValue()
    {
        $stub = $this->createMock(Crypsic::class);

        $stub->expects(self::any())
            ->method('hash')
            ->will($this->returnValue('hash password'));

        $this->assertEquals('hash password', $stub->hash('hash password'));

        $auth = is_string($stub->hash('hash password'));

        $this->assertTrue($auth);
    }

    public function testPasswordAndKeyMustBeSecret()
    {
        $credentials = crypsic::authKey('My Password')
                            ->hash('$2y$10$Foe1ldFKRUYRhDVtmAnlHeeHD7ik575RyaDNA5HxBj9aYQ4PRCp46')
                            ->key('958450dd01075b835698704d4e5a164f');

        $this->assertNull($credentials);
    }

    public function testWithSameKeyDifferentCipherMode()
    {
        $mode1 = crypsic::mode('CBC-128');
        $mode2 = crypsic::mode('CTR-128');

        $secret = 'Please Protect Me';

        $generateKey = crypsic::saveKey('This is my key');
        crypsic::key($generateKey);

        $encrypt1 = crypsic::listen($secret);
        $decrypt1 = crypsic::look($encrypt1);

        /// Other Cipher ///
        $generateKey = crypsic::saveKey('This is my key');
        crypsic::key($generateKey);

        $encrypt2 = crypsic::listen($secret);
        $decrypt2 = crypsic::look($encrypt2);

        $this->assertNotEquals($encrypt1, $encrypt2);
    }

    public function testWithSameKeySameCipherMode()
    {
        $mode1 = crypsic::mode('CBC-128');
        $mode2 = crypsic::mode('CBC-128');

        $secret = 'Please Protect Me';

        $generateKey = crypsic::saveKey('This is my key');
        crypsic::key($generateKey);

        $encrypt1 = crypsic::listen($secret);
        $decrypt1 = crypsic::look($encrypt1);

        /// Other Cipher ///
        $generateKey = crypsic::saveKey('This is my key');
        $mykey = crypsic::key($generateKey);

        $encrypt2 = crypsic::listen($secret);
        $decrypt2 = crypsic::look($encrypt2);

        $this->assertNotEquals($encrypt1, $encrypt2);
    }

    public function testEqualSecretAndEncryptData()
    {
        $secret = 'Please Protect Me';

        $generateKey = crypsic::saveKey('This is my key');
        crypsic::key($generateKey);

        $encrypt = crypsic::listen($secret);
        $decrypt = crypsic::look($encrypt);

        $this->assertEquals($secret, $decrypt);
    }

    public function testDecryptWithWrongKey()
    {
        $secret = 'Please Protect Me';

        $key = '8957aa5d61ecbba69ec35acd69ba06ec';

        crypsic::key($key);

        $decrypt = crypsic::look('UHjvlDwhKfIeNQ1ZLwvw7038vxWqfF+U3vBVhG46K74SeuxZcK/5PaCO2Uk5QqWj6m5yeYmtnqlxck9RV2CFNf87NGoRpMbjcm1hEQlHKuQ=');

        if (!$key) {
            $this->expectException(EqualsException::class);
            $thrown = true;
            $this->assertFalse($thrown);
        }	
    }

    public function testDecryptWithWrongEncryptData()
    {
        $secret = 'Please Protect Me';

        $fake = '8957aa5d61ecbba69ec35acd69ba06ec';

        crypsic::key('8957aa5d61ecbba69ec35acd69ba06ec');

        $decrypt = crypsic::look('UHjvlDwhKfIeNQ1ZLwvw7038vxWqfF+U3vBVhG46K74SeuxZcK/5PaCO2Uk5QqWj6m5yeYmtnqlxck9RV2CFNf87NGoRpMbjcm1hEQlHKuQ=');

        if ($decrypt !== $secret) {
            $this->expectException(EqualsException::class);
            $thrown = true; 
            $this->assertTrue($thrown);
        }	
    }


    public function testDecryptKeyProtectWithPassword()
    {
        $password = 'My Password';
        $secret = 'Please Protect Me';

        crypsic::authKey('My Password')
                ->hash('$2y$10$Foe1ldFKRUYRhDVtmAnlHeeHD7ik575RyaDNA5HxBj9aYQ4PRCp46')
                ->key('958450dd01075b835698704d4e5a164f');

        $data = 'JIV3r+8wyW3CH4cd+D38iBhZdhQeSzlUh3Zbkq+vtQWj7wd2iHZg/3qrQXIARppE3mn/Y2gtvvQXuAPBlBNCN9ZPhFrerp0EoH3Op7a1jcY=';

        $decrypt = crypsic::look($data);

        $this->assertSame($secret, $decrypt);
        $this->assertEquals($secret, $decrypt);
    }

    public function testDecryptKeyProtectWithWrongPassword()
    {
        $password = 'My Password';
        $secret = 'Please Protect Me';

        crypsic::authKey('My Password')
                ->hash('$2y$10$Foe1ldFKRUYRhDVtmAnlHeeHD7ik575RyaDNA5HxBj9aYQ4PRCp46')
                ->key('958450dd01075b835698704d4e5a164f');

        $data = 'JIV3r+8wyW3CH4cd+D38iBhZdhQeSzlUh3Zbkq+vtQWj7wd2iHZg/3qrQXIARppE3mn/Y2gtvvQXuAPBlBNCN9ZPhFrerp0EoH3Op7a1jcY=';

        $decrypt = crypsic::look($data);

        if ($decrypt !== $secret) 
            $this->expectExceptionMessage('Invalid Password');
            $thrown = true; 
            $this->assertTrue($thrown);
    }
}
