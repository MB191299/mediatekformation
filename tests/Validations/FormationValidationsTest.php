<?php

namespace App\Tests\Validations;

use DateTime;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase
{
    public function getFormation(): Formation
    {
        return (new Formation())
            ->setTitle("formation")
            ->setPublishedAt(new DateTime("2022/01/13"));
    }

    public function assertErrors(Formation $formation, int $nbErreursAttendues)
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $errors);
    }

    public function testValidPublishAt()
    {
        $formation = $this->getFormation()->setPublishedAt(null, '2024-02-29 00:17:00');
        $this->assertErrors($formation, 0);
    }

    public function testNonValidPublishAt()
    {
        $futureDate = new \DateTime('tomorrow');
        $formation = $this->getFormation()->setPublishedAt($futureDate);
        $this->assertErrors($formation, 1);
    }
}
