<?php

namespace App\Console\Commands;

use App\Category;
use App\Price;
use App\Step;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\Traits\JsonDecodeTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UpdatePrices extends Command
{
    use JsonDecodeTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:prices';

    /**
     * If the price is more than this value, we make a markup.
     *
     * @var int
     */
    public $priceMoreThen = 50;

    /**
     * Markup for price.
     *
     * @var int
     */
    public $markup = 5;

    /**
     * Step id.
     *
     * @var int
     */
    public $stepId = 0;

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
            ->where('id', '>', 1436)
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->where('is_parsed', 1)
            ->get();

        $endpoint = 'https://www.sellcell.com/devices/ajax_comparison/';

        $this->line('Starting parsing prices...');

        foreach ($devices as $key => $device) {

            $prices = Price::query()->where('category_id', $device->id)->get();

            foreach ($prices as $priceModel) {
                $steps = Step::query()->whereIn('id', $priceModel->getAttribute('steps_ids'))->get();

                $attrs = [];

                foreach ($steps as $step) {
                    $attrs += [
                        $step->attribute => $step->slug
                    ];
                }

                $result = [];

                echo 'Category id: ' . $device->getKey() . PHP_EOL;
                echo 'Try get by attributes: ' . json_encode($attrs) . PHP_EOL;

                try {
                    try {
                        $client = new Client();

                        $slug = str_replace(
                            [
                                'samsung-',
                            ],
                            '',
                            $device->getAttribute('slug')
                        );

                        $response = $client
                            ->request(
                                'POST',
                                $endpoint,
                                [
                                    'headers' => [
                                        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
                                        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9'
                                    ],
                                    'form_params' => [
                                        'device_name' => $slug,
                                        'attributes' => $attrs,
                                        'sort' => 'best_match',
                                    ],
                                ]
                            )
                            ->getBody()
                            ->getContents();
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        
                        dd($e->getMessage());

                        report($e);

                        $this->line("{$device->getAttribute('name')} GET ERROR EXCEPTION");
    
                        
                        $priceModel->update([
                            'updated' => 0,
                        ]);
                    }

                    $result = $this->decodeResult($response) ?? null;

                    if ($result && $result['prices']) {
                        $maxPrice = $this->maxValueInArray($result['prices'], 'price');

                        foreach ($result['prices'] as $price) {
                            if ($price['merchant_short_name'] === 'itsworthmore') {
                                $maxPrice = $price['price'];
                            }
                        }

                        if ($maxPrice > $this->priceMoreThen) {
                            $maxPrice += $this->markup;
                        }

                        // $data = [
                        //     'category_id' => $device->getKey(),
                        //     'steps_ids' => json_encode($ids),
                        //     'price' => $maxPrice,
                        //     'is_parsed' => 1,
                        // ];

                        $priceModel->update([
                            'price' => $maxPrice,
                            'updated' => 1,
                        ]);

                        echo 'Steps ids updated: ' . json_encode($priceModel->getAttribute('steps_ids')) . PHP_EOL;
                    } else {
                        $priceModel->update([
                            'updated' => 1,
                        ]);
                    }
                } catch (\Exception $e) {
                    dd($e->getMessage());
                    
                    Log::info('Parse prices exception: ' . $e->getMessage());

                    $priceModel->update([
                        'updated' => 0,
                    ]);

                    $this->line("{$device->getAttribute('name')} GET ERROR EXCEPTION");
                }
            }

            $maxPriceByCategory = DB::table('prices')->where('category_id', $device->getKey())->max('price');

            $premiumPrices = DB::table('premium_price')->where('category_id', $device->getKey())->get();

            $addToPrice = 0;

            $addPercent = 0;

            $max = 0;

            foreach ($premiumPrices as $premiumPrice) {
                $step = Step::query()->whereKey($premiumPrice->step_id)->first();

                $isCheckbox = $step->stepName->is_checkbox;

                if ($pricePlus = $premiumPrice->price_plus) {
                    if ($isCheckbox) {
                        $addToPrice += $pricePlus;
                    } else {
                        if ($this->stepId !== $step->stepName->id) {
                            if ($max < (float) $premiumPrice->price_plus) {
                                $max = (float) $premiumPrice->price_plus;

                                $addToPrice += $max;
                            }
                        } else {
                            $max = 0;
                        }
                    }
                }

                if ($percentPlus = $premiumPrice->price_percent) {
                    $addPercent += $percentPlus;
                }

                $this->stepId = $step->stepName->id;
            }

            if ($addPercent) {
                $priceAddPercent = ((float) $maxPriceByCategory * $addPercent) / 100;

                $maxPriceByCategory = number_format((float) $maxPriceByCategory + $priceAddPercent, 2, '.', '');
            }

            if ($addToPrice) {
                $maxPriceByCategory = number_format((float) $maxPriceByCategory + $addToPrice, 2, '.', '');
            }

            $device->update(['custom_text' => $maxPriceByCategory]);

            $this->line("{$device->getAttribute('name')}....OK");

            $this->line('Memory now at: ' . memory_get_peak_usage());

        }

        $this->line("Work is done!");
    }

    public function maxValueInArray($array, $keyToSearch)
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
