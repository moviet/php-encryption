<?php
/**
* Functional Testing
* To Test Password ARGON2I 
* We Need Minimum PHP v7.2
*
* PHPUnit v7.5
*/
namespace Moviet\Testing;

use \RuntimeException;
use Moviet\Heavy\Hash\Pwsuit;
use Moviet\Heavy\Exceptions\EqualsException;
use PHPUnit\Framework\TestCase;

class PwsuitTest extends TestCase
{			
    public function testException()
    {
        if (!extension_loaded('openssl')) {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Openssl Extension Unable to load');
        }
        
        if (extension_loaded('mbstring')) {
            $thrown = false;
        }
        
        $this->assertFalse($thrown);    
        
        if (function_exists('hash_equals')) {
            $thrown = false;
        }
        
        $this->assertFalse($thrown);           
    }
    
    public function testPhpVersionCompatible()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '>')) {
            $version = true;
        }
        
        $this->assertTrue($version);
    }
    
    public function testCreateCostFactor()
    {
        $stub = $this->createMock(Pwsuit::class);

        $val1 = $stub->expects(self::any())
                    ->method('cost')
                    ->will($this->returnValue(12));
        
        $val2 = $stub->expects(self::any())
                    ->method('cost')
                    ->will($this->returnValue(24));
        
        $this->assertNotEquals($val1, $val2);
    }

    public function testCreateMemoryCostFactor()
    {
        $stub = $this->createMock(Pwsuit::class);

        $val1 = $stub->expects(self::any())
                    ->method('memory')
                    ->will($this->returnValue(1666));
        
        $val2 = $stub->expects(self::any())
                    ->method('memory')
                    ->will($this->returnValue(1222));
        
        $this->assertNotEquals($val1, $val2);
    }

    public function testCreateTimeCostFactor()
    {
        $stub = $this->createMock(Pwsuit::class);

        $stub->method('time')->willReturn(6);

        $this->assertEquals(6, $stub->time(6));

        if ($stub->time(6) === 6) {
            $time = true;
        }

        $stub->expects(self::any())
            ->method('time')
            ->will($this->returnValue(6));

        $num = is_string($stub->time(6));

        $this->assertTrue($time);
        $this->assertFalse($num);
    }

    public function testCreateThreadFactor()
    {
        $stub = $this->createMock(Pwsuit::class);

        $stub->method('thread')->willReturn(6);

        $this->assertEquals(6, $stub->thread(6));

        if ($stub->thread(6) === 6) {
            $thread = true;
        }

        $stub->expects(self::any())
            ->method('thread')
            ->will($this->returnValue(6));

        $this->assertEquals(6, $stub->thread(6));

        $num = is_string($stub->time(6));

        $this->assertTrue($thread);
        $this->assertFalse($num);
    }

    public function testCheckPasswordLength()
    {
        $checkInfo = Pwsuit::pwinfo('$2y$14$tjFoW2AhxyVRM9UWTKSl1.XEP2.H8PKW8SvL4rqFiu4G23ytqRWIW');

        foreach ($checkInfo as $value) {
            if ($value > 16) {
                $hash = false;
            }
        }

        $this->assertFalse($hash);
    }

    public function testPasswordWithDifferentLength()
    {
        $pass1 = Pwsuit::pwhash('Default','Test Password');

        $pass2 = Pwsuit::cost(10)->pwhash('Default','Test Password');

        $this->assertNotEquals($pass1, $pass2);
    }

    public function testPasswordWithDifferentMode()
    {
        $pass1 = Pwsuit::pwhash('Argon2i','Test Password');

        $pass2 = Pwsuit::memory(1100)->time(4)->thread(4)->pwhash('Argon2i','Test Password');

        $this->assertNotEquals($pass1, $pass2);
    }

    public function testPasswordWithSameModeDifferentResults()
    {
        $pass = Pwsuit::pwhash('Default','Test Password');

        $rehash = Pwsuit::pwhash('Default','Test Password');

        $this->assertNotEquals($pass, $rehash);
    }

    public function testRehashPassword()
    {
        $pass = Pwsuit::cost(10)->pwhash('Default','Test Password');

        $rehash = Pwsuit::pwRehash('Default','Test Password', $pass);
        
        if ($rehash !== $rehash) {
            $this->expectException(EqualsException::class);
            $this->expectExceptionMessage('Pass Invalid');
        }

        $this->assertNotEquals($rehash, $pass);
    }

    public function testTrustPasswordIsVerified()
    {
        $pass = Pwsuit::cost(10)->pwhash('Default','Test Password');

        $trust = Pwsuit::pwtrust('Test Password', $pass);

        $this->assertTrue($trust);
    }
}
