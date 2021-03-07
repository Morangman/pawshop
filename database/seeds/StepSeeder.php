<?php

declare(strict_types = 1);

use Illuminate\Database\Seeder;
use App\Step;

/**
 * Class StepsSeeder
 */
class StepSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $steps = [
            [
                'name' => 'What is the condition of the phone?',
                'items' => [
                    [
                        'name' => 'LIKE NEW',
                        'item_text' => 'Phone still in factory original packaging. Must come with the box and all accessories sealed/untouched.',
                        'slug' => 'new',
                    ],
                    [
                        'name' => 'GOOD',
                        'item_text' => 'Shows light to moderate signs of wear. Contains few light scratches and/or dents.',
                        'slug' => 'working',
                    ],
                    [
                        'name' => 'POOR',
                        'item_text' => 'Shows moderate to excessive signs of wear. Contains excessive scratching, major dents, and/or mild housing damage such as a slightly bent frame.',
                        'slug' => 'poor',
                    ],
                    [
                        'name' => 'FAULTY',
                        'item_text' => 'Cracks (regardless of size) or broken parts on either screen or body of the item.',
                        'slug' => 'broken',
                    ],
                ],
                'is_condition' => 1,
            ],
            [
                'name' => 'Please select the phone\'s carrier',
                'items' => [
                    [
                        'name' => 'Verizon',
                        'slug' => '59',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/59.png',
                    ],
                    [
                        'name' => 'AT&T',
                        'slug' => '48',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/48.png',
                    ],
                    [
                        'name' => 'T-Mobile',
                        'slug' => '30',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/30.png',
                    ],
                    [
                        'name' => 'Sprint',
                        'slug' => '49',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/49.png',
                    ],
                    [
                        'name' => 'Other',
                        'slug' => '104',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/104.png',
                    ],
                    [
                        'name' => 'Unlocked',
                        'slug' => '29',
                        'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/29.png',
                    ],
                ],
            ],
            [
                'name' => 'What accessories will be included?',
                'items' => [
                    [
                        'name' => 'Original Box',
                        'price_plus' => '1',
                    ],
                    [
                        'name' => 'Powercable',
                        'price_plus' => '1',
                    ],
                ],
                'is_checkboxes' => 1,
            ],
        ];

        foreach ($steps as $step) {
            Step::query()->create($step);
        }
    }
}
