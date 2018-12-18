<?php
namespace Moviet\Heavy\Hash;

interface Suitin
{
	public function cost($cost);

	public function memory($memory);

	public function time($time);

	public function thread($thread);

	public function pwHash($mode, $password);

	public function pwRehash($mode, $password, $hash);

	public function pwInfo($hash);
}
