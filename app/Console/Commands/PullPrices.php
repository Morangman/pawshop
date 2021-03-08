<?php

namespace App\Console\Commands;

use App\Category;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\Traits\JsonDecodeTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PullPrices extends Command
{
    use JsonDecodeTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse devices prices by steps from https://www.sellcell.com/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->line('Preparing for parsing prices...');

        Schema::disableForeignKeyConstraints();

        DB::table('prices')->truncate();

        Schema::enableForeignKeyConstraints();

        $devices = Category::query()
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->get();

        $client = new Client();

        $endpoint = 'https://www.sellcell.com/devices/ajax_comparison/';

        $this->line('Starting parsing prices...');

        foreach ($devices as $device) {
            $attributes = [];

            foreach ($device->steps()->get()->toArray() as $step) {
                foreach ($step['items'] as $item){
                    $attributes[$item['attribute']][] = [
                        $item['attribute'] => $item['slug']
                    ];
                }
            }

            $combinations = $this->array_cartesian_product($attributes);

            foreach ($combinations as $key => $combination) {
                $attrs = [];

                foreach ($combination as $attributes) {
                    $attrs += $attributes;
                }

                $result = [];

                $response = $client
                    ->request(
                        'POST',
                        $endpoint,
                        [
                            'form_params' => [
                                'device_name' => $device->getAttribute('slug'),
                                'attributes' => $attrs,
                                'sort' => 'best_match',
                            ],
                        ]
                    )
                    ->getBody()
                    ->getContents();

                $result = $this->decodeResult($response);

                if ($result && $result['prices']) {
                    $maxPrice = $this->maxValueInArray($result['prices'], 'price');

                    foreach ($result['prices'] as $price) {
                        if ($price['merchant_short_name'] === 'itsworthmore') {
                            $maxPrice = $price['price'];
                        }
                    }

                    $data = [
                        'category_id' => $device->getKey(),
                        'condition' => isset($attrs['condition']) ? $attrs['condition'] : null,
                        'network' => isset($attrs['network']) ? $attrs['network'] : null,
                        'capacity' => isset($attrs['capacity']) ? $attrs['capacity'] : null,
                        'price' => $maxPrice,
                    ];

                    DB::table('prices')->insert($data);
                }
            }

            $this->line("{$device->getAttribute('name')}....OK");
        }

        $this->line("Work is done!");
    }

    function array_cartesian_product($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    function maxValueInArray($array, $keyToSearch)
    {
        $currentMax = NULL;
        foreach($array as $arr)
        {
            foreach($arr as $key => $value)
            {
                if ($key == $keyToSearch && ($value >= $currentMax))
                {
                    $currentMax = $value;
                }
            }
        }

        return $currentMax;
    }
}
