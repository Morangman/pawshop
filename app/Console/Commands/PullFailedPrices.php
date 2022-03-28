<?php

namespace App\Console\Commands;

use App\Category;
use App\Price;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\Traits\JsonDecodeTrait;
use App\Step;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PullFailedPrices extends Command
{
    use JsonDecodeTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:f-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart parse failed prices by steps from https://www.sellcell.com/';

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
        $this->line('Preparing for parsing failed prices...');

        DB::connection()->disableQueryLog();

        $client = new Client();

        $endpoint = 'https://www.sellcell.com/devices/ajax_comparison/';

        $this->line('Starting parsing failed prices...');

        $failedPrices = DB::table('failed_prices')->get();

        foreach ($failedPrices as $fprice) {
                    try {
                        $slug = $fprice->device_slug;

                        if (Str::contains($slug, 'huawei-google-nexus-6p')) {
                            $slug = 'google-nexus-6p';
                        }

                        if (Str::contains($slug, 'motorola-google-nexus-6')) {
                            $slug = 'google-nexus-6';
                        }

                        if (Str::contains($slug, 'lg-google-nexus-5x')) {
                            $slug = 'google-nexus-5x';
                        }

                        if (Str::contains($slug, 'lg-google-nexus-4')) {
                            $slug = 'google-nexus-4';
                        }

                        if (Str::contains($slug, 'lg-google-nexus-5')) {
                            $slug = 'google-nexus-5';
                        }

                        if (Str::contains($slug, 'htc-google-nexus-one')) {
                            $slug = 'google-nexus-one';
                        }

                        if (Str::contains($slug, 'samsung-google-nexus-s')) {
                            $slug = 'google-nexus-s';
                        }

                        $response = $client
                            ->request(
                                'POST',
                                $endpoint,
                                [
                                    'form_params' => [
                                        'device_name' => $slug,
                                        'attributes' => json_decode($fprice->attributes, true),
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

                            $ids = [];

                            foreach (json_decode($fprice->attributes, true) as $key => $attr) {
                                $stepId = Step::query()->where('slug', $attr)->where('attribute', $key)->first()->getKey();
    
                                $ids[] = $stepId;
                            }

                            // $data = [
                            //     'category_id' => $fprice->getAttribute('category_id'),
                            //     'condition' => isset($attrs['condition']) ? $attrs['condition'] : null,
                            //     'network' => isset($attrs['network']) ? $attrs['network'] : null,
                            //     'capacity' => isset($attrs['capacity']) ? $attrs['capacity'] : null,
                            //     'price' => $maxPrice,
                            // ];

                            // DB::table('prices')->insert($data);

                            $priceModel = Price::query()
                                ->where('category_id', $fprice->category_id)
                                ->whereJsonContains('steps_ids', $ids)
                                ->first();

                            $priceModel->update([
                                'price' => $maxPrice,
                            ]);

                            DB::table('failed_prices')->where('id', $fprice->id)->delete();
                        }
                    } catch (\Exception $e) {
                        Log::info('Parse FAILED prices exception: ' . $e->getMessage());

                        $this->line("{$fprice->device_name} GET ERROR EXCEPTION");

                        continue;
                    }

                $this->line("{$fprice->device_name}....OK");

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
