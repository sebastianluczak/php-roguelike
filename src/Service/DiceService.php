<?php

namespace App\Service;

use Faker\Factory as FakerFactory;
use Faker\Generator;

class DiceService
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create('en_EN');
    }

    public function roll(int $sides = 6): int
    {
        return $this->faker->numberBetween(1, $sides);
    }
}