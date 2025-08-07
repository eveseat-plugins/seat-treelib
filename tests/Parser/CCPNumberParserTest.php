<?php

namespace RecursiveTree\Seat\TreeLib\Tests\Parser;

use Orchestra\Testbench\TestCase;
use RecursiveTree\Seat\TreeLib\Parser\Parser;

class CCPNumberParserTest extends TestCase
{

    private function assertBigNumber(float $expected, string $number)
    {
        $expr = Parser::BIG_NUMBER_REGEXP;
        if(preg_match("/$expr/", $number, $match) != 1) {
            $this->fail("'$number' does not match as a BIG_NUMBER");
        }
        $this->assertEquals($expected,Parser::parseBigNumber($number));
    }

    public function simpleBigNumber()
    {
        $this->assertBigNumber(5, Parser::parseBigNumber("5"));
        $this->assertBigNumber(10000, Parser::parseBigNumber("10’000"));
    }

    public function testBigNumberDecimalDetection()
    {
        // English: price of item
        $this->assertBigNumber(2.01, Parser::parseBigNumber("2.01"));
        $this->assertBigNumber(222222.01, Parser::parseBigNumber("222,222.01"));
        // English: amount
        $this->assertBigNumber(2012, Parser::parseBigNumber("2,012"));
        $this->assertBigNumber(222222012, Parser::parseBigNumber("222,222,012"));


        // German: price of an item
        $this->assertBigNumber(2.01, Parser::parseBigNumber("2,01"));
        $this->assertBigNumber(222222.01, Parser::parseBigNumber("222.222,01"));
        // German: amount
        $this->assertBigNumber(2012, Parser::parseBigNumber("2.012"));
        $this->assertBigNumber(222222012, Parser::parseBigNumber("222.222.012"));
    }

    public function testGermanInventoryWindow()
    {
        $result = Parser::parseItems("Luminous Kernite	2.012	Kernite		2.414,40 m3	582.292,92 ISK");
        $this->assertCount(1, $result->items);
        $this->assertEquals(17452, $result->items[0]->getTypeID());
        $this->assertEquals(2012, $result->items[0]->amount);
        $this->assertEquals(1.2, $result->items[0]->volume);
        $this->assertEquals(582292.92, $result->items[0]->ingamePrice);
        $this->assertFalse($result->items[0]->is_named);
    }

    public function testEnglishInventoryWindow()
    {
        $result = Parser::parseItems("Capital Construction Parts	5	Capital Construction Components	Commodity			10’000 m3	69’520’490.05 ISK");
        $this->assertCount(1, $result->items);
        $this->assertEquals(21037, $result->items[0]->getTypeID());
        $this->assertEquals(5, $result->items[0]->amount);
        $this->assertEquals(2000, $result->items[0]->volume);
        $this->assertEquals(69520490.05, $result->items[0]->ingamePrice);
        $this->assertFalse($result->items[0]->is_named);

        $result = Parser::parseItems("Liquid Ozone	20’000	Ice Product	Material			8’000 m3		None	2’510’800.00 ISK");
        $this->assertCount(1, $result->items);
        $this->assertEquals(16273, $result->items[0]->getTypeID());
        $this->assertEquals(20000, $result->items[0]->amount);
        $this->assertEquals(0.4, $result->items[0]->volume);
        $this->assertEquals(2510800, $result->items[0]->ingamePrice);
        $this->assertFalse($result->items[0]->is_named);
    }
}