<?php

namespace App\Console\Commands;

use App\Category;
use App\CategoryStep;
use App\Order;
use App\Setting;
use App\Step;
use App\StepName;
use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class PullDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:devices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse devices from https://www.sellcell.com/';

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
        // парсинг на прямую с itsworthmore
        // $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        // $iphonesCrawler = $client->request('GET', 'https://www.itsworthmore.com/sell/iphone/iphone-13-pro-max');

        // $scripts = [];
        // $html = $iphonesCrawler->html();
        
        // preg_match_all("~<script[^>]*>\K[^<]*(?=</script>)~i", $html, $scripts);

        // $productPricingExtraData = Arr::get($scripts, '0.6', []);

        // $base64Value = $this->matchin($productPricingExtraData, "'pricingData'" . '    : "', '",');

        // $decoded = base64_decode($base64Value[0][0]);

        // dd(json_decode($decoded, true));


        $settings = Setting::latest('updated_at')->first();

        $basePath = $settings->getAttribute('general_settings')['base_path'];

        $iphonesPoint = $settings->getAttribute('general_settings')['iphones_point'];

        $samsungPoint = $settings->getAttribute('general_settings')['samsung_point'];

        $googlePhones = $settings->getAttribute('general_settings')['google_phones'];

        $lgPhones = $settings->getAttribute('general_settings')['lg_phones'];

        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        $iphonesCrawler = $client->request('GET', $iphonesPoint);

        $samsungCrawler = $client->request('GET', $samsungPoint);

        $googleCrawler = $client->request('GET', $googlePhones);

        $lgCrawler = $client->request('GET', $lgPhones);

        $ipadsPoint = $settings->getAttribute('general_settings')['ipads_point'];

        $ipadsCrawler = $client->request('GET', $ipadsPoint);

        $iphoneCategory = Category::query()->where('id', 1)->first();
        $samsungCategory = Category::query()->where('id', 28)->first();
        $googleCategory = Category::query()->where('id', 648)->first();
        $lgCategory = Category::query()->where('id', 488)->first();

        $ipadsCategory = Category::query()->where('id', 1087)->first();

        //PULL IPHONE
        $iphones = $iphonesCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $iphoneStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $iphoneStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => $nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/apple-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $this->savePhone($iphoneCategory, $iphones);

        $this->line('Iphones was parsed...');

        //PULL SAMSUNG
        $samsungPhones = $samsungCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {
            $samsungStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));


            $capacities = $samsungStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });


            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => $nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/samsung-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $this->savePhone($samsungCategory, $samsungPhones);

        $this->line('Samsung phones was parsed...');

        //PULL LG
        $lg = $lgCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $lgStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $lgStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'LG '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/lg-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $this->savePhone($lgCategory, $lg);

        $this->line('LG phones was parsed...');

        //PULL GOOGLE phones
        $google = $googleCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $googleStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $googleStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            $slug = preg_replace('/^\/(.+?)\/google-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href'));

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

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Google '.$nodeCrawler->filter('.h4')->text(),
                'slug' => $slug,
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $this->savePhone($googleCategory, $google);

        $this->line('Google phones was parsed...');

        //IPAD TABLETS
        $ipads = $ipadsCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $ipadStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $ipadStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            $networks = $ipadStepsCrawler->filter('.network-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $ntwrks = [];

                return $ntwrks[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'network' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => $nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/apple-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
                'networks' => $networks,
            ];
        });

        $this->saveIpads($ipadsCategory, $ipads);

        $this->line('IPads was parsed...');



        $appleWatchPoint = $settings->getAttribute('general_settings')['appleWatch_point'];

        $appleWatchCrawler = $client->request('GET', $appleWatchPoint);

        $watchCategory = Category::query()->where('id', 1436)->first();

        //APPLE WATCH
        $appleWatch = $appleWatchCrawler->filter('.devices')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $category = $nodeCrawler->filter('h3')->text();

            $items = $nodeCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client, $category) {
                $appleWatchStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

                $capacities = $appleWatchStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                    $cpies = [];

                    return $cpies[] = [
                        'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                        'attribute' => $slug ? 'capacity' : null,
                        'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                    ];
                });

                $networks = $appleWatchStepsCrawler->filter('.network-logos-sprite')->each(function (Crawler $nodeCrawler) {
                    $ntwrks = [];

                    return $ntwrks[] = [
                        'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                        'attribute' => $slug ? 'network' : null,
                        'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                    ];
                });

                return [
                    'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                    'title' => 'Apple '.$nodeCrawler->filter('.h4')->text(),
                    'slug' =>   preg_replace('/^\/(.+?)\/apple-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                    'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                    'capacities' => $capacities,
                    'networks' => $networks,
                ];
            });

            return [
                $category => $items
            ];
        });

        foreach ($appleWatch as $device) {
            foreach ($device as $key => $items) {
                if ($key === 'Sell Apple Watch Series 7') {
                    $this->saveWatch($watchCategory, $items);
                }
            }
        }
    }

    public function matchin($input, $start, $end){
        $in      = array('/');
        $out     = array('\/');
        $startCh = str_replace($in, $out, $start);
        $endCh   = str_replace($in, $out, $end);
    
        $pattern = '/(?<='.$startCh.').*?(?='.$endCh.')/sim';
        // or you can use 
        // $pattern = '/(?<='.$startCh.')[\\s\\S]*?(?='.$endCh.')/';
    
        preg_match_all($pattern, $input, $result);
        return array($result[0]);
    }

    public function savePhone($iphoneCategory, $iphones) {
        $maxPrice = 5;

        foreach ($iphones as $iphone) {

            //проверяем есть ли значение размера памяти
            if ($iphone['capacities'] != []) {
                foreach ($iphone['capacities'] as $capacity) {
                    $capacityStep = Step::query()
                        ->where('name_id', 10)
                        ->where('slug', $capacity['slug'])
                        ->first();

                    if (!$capacityStep) {
                        $capacityStep = Step::query()->create([
                            'name_id' => 10,
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $iphone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $iphoneCategory->getKey(),
                    'name' => $iphone['title'],
                    'slug' => $iphone['slug'],
                    'custom_text' => $iphone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $iphone['price'] < $maxPrice ? 1 : 0,
                ]);

                //премиум
                $premiumAccessories = [29, 30, 31, 32];

                DB::table('premium_price')->insert([
                    'category_id' => $category->getKey(),
                    'step_id' => 1,
                    'price_percent' => 10,
                ]);

                foreach ($premiumAccessories as $id) {
                    DB::table('premium_price')->insert([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'price_plus' => 1,
                    ]);
                }

                //состояние
                $conditionsIds = [1, 2, 3, 4, 5];

                foreach ($conditionsIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 1,
                    ]);
                }

                //операторы связи
                $carriersIds = [23, 24, 25, 26, 27, 28];

                foreach ($carriersIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 2,
                    ]);
                }

                //размеры памяти
                foreach ($iphone['capacities'] as $capacity) {
                    $capacityStep = Step::query()
                        ->where('name_id', 10)
                        ->where('slug', $capacity['slug'])
                        ->first();

                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $capacityStep->getKey(),
                        'sort_order' => 3,
                    ]);
                }

                //допы
                $accesorriesIds = [29, 30, 31, 32];

                foreach ($accesorriesIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 4,
                    ]);
                }

                //рабочий или нет
                $functionslIds = [33, 34];

                foreach ($functionslIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 5,
                    ]);
                }

                $media = $category->addMediaFromUrl($iphone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }
    }

    public function saveIpads($ipadsCategory, $iphones) {
        $maxPrice = 5;

        foreach ($iphones as $iphone) {

            //проверяем есть ли значение размера памяти
            if ($iphone['capacities'] != []) {
                foreach ($iphone['capacities'] as $capacity) {
                    $capacityStep = Step::query()
                        ->where('name_id', 10)
                        ->where('slug', $capacity['slug'])
                        ->first();

                    if (!$capacityStep) {
                        $capacityStep = Step::query()->create([
                            'name_id' => 10,
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            //проверяем
            if ($iphone['networks'] != []) {
                foreach ($iphone['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', 7)
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => 7,
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $iphone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $ipadsCategory->getKey(),
                    'name' => $iphone['title'],
                    'slug' => $iphone['slug'],
                    'custom_text' => $iphone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $iphone['price'] < $maxPrice ? 1 : 0,
                ]);

                //премиум
                $premiumAccessories = [29, 30, 31, 32];

                DB::table('premium_price')->insert([
                    'category_id' => $category->getKey(),
                    'step_id' => 1,
                    'price_percent' => 10,
                ]);

                foreach ($premiumAccessories as $id) {
                    DB::table('premium_price')->insert([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'price_plus' => 1,
                    ]);
                }

                //состояние
                $conditionsIds = [1, 2, 3, 4, 5];

                foreach ($conditionsIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 1,
                    ]);
                }

                //операторы связи
                foreach ($iphone['networks'] as $network) {
                    $networkStep = Step::query()
                        ->where('name_id', 7)
                        ->where('slug', $network['slug'])
                        ->first();

                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $networkStep->getKey(),
                        'sort_order' => 2,
                    ]);
                }

                //размеры памяти
                foreach ($iphone['capacities'] as $capacity) {
                    $capacityStep = Step::query()
                        ->where('name_id', 10)
                        ->where('slug', $capacity['slug'])
                        ->first();

                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $capacityStep->getKey(),
                        'sort_order' => 3,
                    ]);
                }

                //допы
                $accesorriesIds = [29, 30, 31, 32];

                foreach ($accesorriesIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 4,
                    ]);
                }

                //рабочий или нет
                $functionslIds = [33, 34];

                foreach ($functionslIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 5,
                    ]);
                }

                $media = $category->addMediaFromUrl($iphone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }
    }

    public function saveWatch($watchCategory, $iphones) {
        $maxPrice = 5;

        foreach ($iphones as $iphone) {

            //проверяем есть ли значение размера памяти
            if ($iphone['capacities'] != []) {
                foreach ($iphone['capacities'] as $capacity) {
                    $capacityStep = Step::query()
                        ->where('name_id', 10)
                        ->where('slug', $capacity['slug'])
                        ->first();

                    if (!$capacityStep) {
                        $capacityStep = Step::query()->create([
                            'name_id' => 10,
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            //проверяем
            if ($iphone['networks'] != []) {
                foreach ($iphone['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', 7)
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => 7,
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $iphone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $watchCategory->getKey(),
                    'name' => $iphone['title'],
                    'slug' => $iphone['slug'],
                    'custom_text' => $iphone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $iphone['price'] < $maxPrice ? 1 : 0,
                ]);

                //премиум
                $premiumAccessories = [13, 14];
                $premiumBands = [15, 16, 17, 18, 19, 20, 21, 22];

                DB::table('premium_price')->insert([
                    'category_id' => $category->getKey(),
                    'step_id' => 1,
                    'price_percent' => 10,
                ]);

                foreach ($premiumAccessories as $id) {
                    DB::table('premium_price')->insert([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'price_plus' => 1,
                    ]);
                }

                foreach ($premiumBands as $id) {
                    DB::table('premium_price')->insert([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'price_plus' => 35,
                    ]);
                }

                //состояние
                $conditionsIds = [1, 2, 3, 4, 5];

                foreach ($conditionsIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 1,
                    ]);
                }

                //операторы связи
                foreach ($iphone['networks'] as $network) {
                    $networkStep = Step::query()
                        ->where('name_id', 7)
                        ->where('slug', $network['slug'])
                        ->first();

                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $networkStep->getKey(),
                        'sort_order' => 2,
                    ]);
                }

                //допы
                $accesorriesIds = [13, 14];

                foreach ($accesorriesIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 3,
                    ]);
                }

                //допы
                $smartwatchBandsIds = [15, 16, 17, 18, 19, 20, 21, 22];

                foreach ($smartwatchBandsIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 4,
                    ]);
                }

                //рабочий или нет
                $functionslIds = [33, 34];

                foreach ($functionslIds as $id) {
                    CategoryStep::query()->create([
                        'category_id' => $category->getKey(),
                        'step_id' => $id,
                        'sort_order' => 5,
                    ]);
                }

                $media = $category->addMediaFromUrl($iphone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }
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
}
