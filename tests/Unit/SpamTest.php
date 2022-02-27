<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;
// use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    // use DatabaseMigrations;
    
    /**test */
    public function test_check_for_invalid_keyword()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

     /**test */
     public function test_check_for_any_key_being_held_down()
     {
         $spam = new Spam();
 
         $this->expectException('Exception'); 
 
         $spam->detect('Hello world aaaaaaaa');
     }
}
