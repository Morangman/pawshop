<?php

namespace App\Console\Commands;

use App\Category;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\Traits\JsonDecodeTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public $error = false;

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
     * @throws \Exception
     */
    public function handle()
    {
        $this->line('Preparing for parsing prices...');

//        Schema::disableForeignKeyConstraints();
//
//        DB::table('prices')->truncate();
//
//        Schema::enableForeignKeyConstraints();

        DB::connection()->disableQueryLog();

        $devices = Category::query()
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->get();

        $client = new Client();

        $endpoint = 'https://www.sellcell.com/devices/ajax_comparison/';

        $this->line('Starting parsing prices...');

        $firstStart = DB::table('prices')->get()->isEmpty();

        if (!$firstStart) {
            $this->line('Restart detected...');

            $this->line('Finding the last item...');

            foreach ($devices as $key => $device) {
                $existCategory = DB::table('prices')->where('category_id', $device->getKey())->exists();

                if (!$existCategory) {
                    $device = $devices[$key - 1];

                    $this->line("Continue parsing from id: {$device->getKey()}...");

                    DB::table('prices')->where('category_id', $device->getKey())->delete();

                    $firstStart = true;

                    break;
                }
            }
        }

        foreach ($devices as $key => $device) {
            $existCategory = DB::table('prices')->where('category_id', $device->getKey())->exists();

//            if ($key > 1) {
//                throw new \Exception();
//            }

            if (!$existCategory) {
                $attributes = [];

                foreach ($device->steps()->get()->toArray() as $step) {
                    foreach ($step['items'] as $item){
                        $attributes[$item['attribute']][] = [
                            $item['attribute'] => $item['slug']
                        ];
                    }
                }

                $combinations = $this->array_cartesian_product($attributes);

                foreach ($combinations as $combination) {
                    $attrs = [];

                    foreach ($combination as $attributes) {
                        $attrs += $attributes;
                    }

                    $result = [];

                    try {
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

                        $result = $this->decodeResult($response) ?? null;

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
                    } catch (\Exception $e) {
                        Log::info('Parse prices exception: ' . $e->getMessage());

                        $this->error = true;

                        $this->line("{$device->getAttribute('name')} GET ERROR EXCEPTION");

                        DB::table('failed_prices')->insert([
                            'category_id' => $device->getKey(),
                            'device_name' => $device->getAttribute('name'),
                            'device_slug' => $device->getAttribute('slug'),
                            'attributes' => json_encode($attrs),
                        ]);

                        continue;
                    }

//                    if ($this->error === true) {
//                        $this->line("{$device->getAttribute('name')} GET ERROR EXCEPTION");
//
//                        DB::table('failed_prices')->insert([
//                            'category_id' => $device->getKey(),
//                            'device_name' => $device->getAttribute('name'),
//                            'device_slug' => $device->getAttribute('slug'),
//                            'attributes' => $attrs,
//                        ]);
//
//                        $this->error = false;
//                    }
                }

                $this->line("{$device->getAttribute('name')}....OK");

                $this->line('Memory now at: ' . memory_get_peak_usage());
            }
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
