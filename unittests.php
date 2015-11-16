<?php

require_once "roman-numerals.php";

class HabitTest extends PHPUnit_Framework_TestCase
{
	public function test1()
	{
	$this->markTestSkipped();
	$this->assertSame('I', toRoman(1));
	}

	public function test2()
	{
	$this->markTestSkipped();
	$this->assertSame('II', toRoman(2));
	}

	public function test3()
	{
	$this->markTestSkipped();
	$this->assertSame('III', toRoman(3));
	}

	public function test4()
	{
	$this->markTestSkipped();
	$this->assertSame('IV', toRoman(4));
	}

	public function test5()
	{
	$this->markTestSkipped();
	$this->assertSame('V', toRoman(5));
	}

	public function test6()
	{
	$this->markTestSkipped();
	$this->assertSame('VI', toRoman(6));
	}
}

