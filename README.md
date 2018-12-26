Crypsic - A speed metal encryption library for php
======================================================
[![Build Status](https://travis-ci.org/moviet/php-encryption.svg?branch=master)](https://travis-ci.org/moviet/php-encryption)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)
[![Usage](https://img.shields.io/badge/usage-easy-ff69b4.svg)](https://github.com/moviet/php-encryption)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moviet/php-encryption/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moviet/php-encryption/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/moviet/php-encryption/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

#### Crypsic is made to be Fast and Secure By Default

This is pretty suits for **PHP devlovers** and also built up for standalone like pro,   
our sources does very simply, included **Crypsic** and **Pwsuit** for supporting native Ops

## Requirements

*	**PHP v5.6** (non-tested) or **PHP v7.0+** (tested)
*	Openssl extensions must be enable
*	Composer autoload [PSR4](https://www.php-fig.org/psr/psr-4/)
*	Composer for installation

## Let's Start

#### Installation
```
composer require "moviet/php-encryption"
```

## Best Practices

*	Do not ever encrypt **a password**
*	Use our instant **`Pwsuit`** to protect a password that supply _modern hashes_
*	**Pwsuit** dedicated for non-coverable performed, so do not forget the password
*	Do not use _`a same key`_ for many secret informations, you can do it well
*	You may need a concern to manage the **_keys_** correctly, it must be treat a safely
*	`Encryption` doesn't same as s-e-c-u-r-i-t-y, so do not construct a bullet proof
*	You must follow the standard security design that suitable for your system

## Features

*	A Symmetric Crypto
*	Customable Cipher Modes
*	Instant Encrypt And Decrypt
*	Auto Generate Secure Random Key
*	Suitable Password Algorithms
*	Secure A Key With Password

#### A Symmetric Crypto

It's meant the current crypto available is only for crafting symmetric encryption

#### Customable Cipher Modes

* You can modify the cipher modes using our own mode functions like
  ```php
  Crypsic::mode('CBC-128') // AES-128-CBC
  ```

  this is **optional** usage, if nothing, it will set to ```AES-256-CBC``` as **default mode**
  
  **notes** :
  > If you want to set mode, _please use one for one operation_, if you don't know we're highly recommended that you **_ignore it_**, and set as default mode for easy usage.  

#### Instant Encrypt And Decrypt

* You can encrypt a secret ID, texts or any others secret information like
   ```php
   Crypsic::listen('My card number 9999-6666-6666-9999')
   ```

* And simply decrypt your secret information like
   ```php
   $mySecretData = 'Dada/nanana367OYeyeyyHola666HoopYeYEYsipp+imo27blablabla'

    Crypsic::look($mySecretData)
    ```

#### Auto Generate Secure Random Key

* You can not decrypt your secret information without a key, so you must create a key at first
   ```php
   Crypsic::saveKey('This is new key please make em zig-zag, bilbo')
   ```

   **Notes** : don't forget to generate a long and heavy characters for your key
	 
   it will auto calculate your key with _Cryptographically Secure Random_ functions 
   
	 for PHP v7.0+ and Openssl pseudo random for PHP v5.6 by very happier

* After you save a key belong encryption data, so you can confirm like so
   ```php
   Crypsic::key('edfes73ccd0191jbabbdbab0101bdbeb10290abbaba1010edsf820')
   ```

#### Suitable Password Algorithms

* You can dynamically set a Password like so
  ```php
  Pwsuit::pwhash('Default','hello this is my password')
  ```

* or custom like
  ```php
  Pwsuit::cost(26)->pwhash('Default','Yes dont blow my head')
  ```

  on above will equivalent like

  `password_hash('My password', PASSWORD_DEFAULT, ['cost'=>26])`


* If you use new PHP v7.2+ you can try **_Modern Hashes_** like
  ```php
  Pwsuit::pwhash('Argon2i','my Argon password is dadada')
  ```

* or any custom like
  ```php
  Pwsuit::memory(4024)
  ->time(4)
  ->thread(4)
  ->pwhash('Argon2id','Hey bob this is my password')
  ```

  it will give you a **nicely smile** by PHP _'out of the box'_

#### Secure A Key With Password

* We attach new crazybility to protect your secret with password like below
   ```php
   // First create Keystore and save
   Crypsic::saveKey('let me burn the typos')
   
   // Then create password and save
   Pwsuit::cost(16)->pwhash('Default','MyPassword')
   ```

   **Notes** : 
   > a **'cost'** length is optional, if higher may have **slow**, but that was better

* Then to decrypt and verify your secret using **key with password** just simply
   ```php
   $postPassword = $_POST['password']

   Crypsic::authKey($postPassword)->hash('My Data Hashed Password')->key('My key')

   Crypsic::look('My Encrypt Data')
   ```

   **Notes** : 
   > Hash and encrypt data has **different results**, please use correctly

* You can also use this lib as standalone to generate password and to verify
   ```php
   Pwsuit::pwTrust($myPassword, $dataPassword) // Return Boolen
   ```

* Refresh the old password hashed using like
   ```php
   Pwsuit::pwRehash('Default', $myPassword, $dataPassword)
   ```

* Ensuring the current hashed data that you've decorated

   ```php
   $info = Pwsuit::pwInfo($my_data_hashed) \\ see dump output with yaayy
   ```

## Happy Usage

#### Encrypt And Decrypt With No Password :
```php
require '__DIR__' . '/vendor/autoload.php';

use Moviet\Heavy\Crypsic;
use Moviet\Heavy\Hash\Pwsuit;

/*  
* Create a long and burn your typos, whatever
*/
$mykey = Crypsic::saveKey('Something a heavy key');

// output : c185128d2ae131b3ecf25779d2ef6120a6d9aa53ea5f422e0e2f6e97385954e9

Crypsic::key($mykey); 

$encrypt = Crypsic::listen('this is new metal song : 9999-8888-6666-1717'); 

// output : J7A2jpefNGp8HBFH0i1Xon5l59EnGFs8zFWdcMlZ1BQ4cYhNv+awNMOLZMcehkc2k6coPlN1oprVCTZPC60t6p5JvLcZHxAPVC5v08XHIYss+yTuLuYZ5CH6RfDpaZzZ

$decrypt = Crypsic::look($encrypt); 

// output : this is new metal song : 9999-8888-6666-1717
```

#### Encrypt And Decrypt With Password :

You may want to arrangement the key from sabotage, please follow this rockly :metal:

```php
require '__DIR__' . '/vendor/autoload.php';

use Moviet\Heavy\Crypsic;
use Moviet\Heavy\Hash\Pwsuit;

$thor = Crypsic::saveKey('Do you know locky');

$tonyStark = Pwsuit::pwhash('Default','I know spiderman with Bob');

// Save the Output : $2y$14$yUwjHQmnOeZyHWCcA5mlE.t3nVySA5NomMGmptkbNG170T3IkGQH.

$jarvish = $_POST['password'];

$captainAfrica = Crypsic::authKey($jarvish)->hash($tonyStark)->key($thor);

$thanos = Crypsic::look($captainAfrica); // and thanos doesn't have any idea => who is bob

```
### Cipher Modes :

| Attributes       | Modes         | 
| ---------------- |:-------------:| 
| CBC-256          |  AES-256-CBC  |
| CBC-192          |  AES-192-CBC  | 
| CBC-128          |  AES-128-CBC  | 
| CTR-256          |  AES-256-CTR  |
| CTR-192          |  AES-192-CTR  | 
| CTR-128          |  AES-128-CTR  |

### Hash Algorithms :

| Default         | Value   | 
|:--------------- |:-------:| 
| cost            | 14      | 
| memory_cost     | 1666    | 
| time_cost       | 6       |
| threads         | 6       |

| Attributes     | Modes                | 
| -------------- |---------------------:| 
| Default        | PASSWORD_DEFAULT     |
| Argon2i        | PASSWORD_ARGON2I     | 
| Argon2d        | PASSWORD_ARGON2D     | 
| Argon2id       | PASSWORD_ARGON2ID    |

## Conclusion

By descriptions on above, you may have a short picture of how easy to use this lib

How secure is this ?
- you do not worry about it, even on production, it can do it well
- if you use for **commercial** projects please follow the best practises

## License

`Moviet/php-encryption` is released under the MIT public license. See the enclosed LICENSE for details.
