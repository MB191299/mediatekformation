<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2024-03-13"));
        $this->assertEquals("13/03/2024", $formation->getPublishedAtString());
    }
}
