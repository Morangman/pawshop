<?php

namespace App\Console\Commands;

use App\Category;
use App\Order;
use App\Step;
use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class PullData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape data from https://www.sellcell.com/';

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
     */
    public function handle()
    {
        $this->line('Preparing for parsing...');

        Category::query()->each(function ($query) {
            $query->clearMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);
        });

        Schema::disableForeignKeyConstraints();

        DB::table('category_step')->truncate();
        DB::table('steps')->truncate();
        DB::table('categories')->truncate();

        Schema::enableForeignKeyConstraints();

        $conditionStep = Step::query()->create([
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
        ]);

        $carrierStep = Step::query()->create([
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
        ]);

        $this->line('Start parsing devices...');

        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        $basePath = 'https://www.sellcell.com';

        $phoneImage = '/client/images/phone.png';

        $iphonesPoint = 'https://www.sellcell.com/sell-iphone/';

        $samsungPoint = 'https://www.sellcell.com/sell/samsung-phone/';

        $htcPhones = 'https://www.sellcell.com/sell/htc-phone/';

        $motorolaPhones = 'https://www.sellcell.com/sell/motorola-phone/';

        $lgPhones = 'https://www.sellcell.com/sell/lg-phone/';

        $onePlusPhones = 'https://www.sellcell.com/sell/oneplus-phone/';

        $googlePhones = 'https://www.sellcell.com/sell/google-phone/';

        $sonyPhones = 'https://www.sellcell.com/sell/sony-phone/';

        $blackBerryPhones = 'https://www.sellcell.com/blackberry/';

        $huaweiPhones = 'https://www.sellcell.com/huawei/';

        $kyoceraPhones = 'https://www.sellcell.com/kyocera/';

        $ztePhones = 'https://www.sellcell.com/zte/';

        $xiaomiPhones = 'https://www.sellcell.com/xiaomi/';

        $razerPhones = 'https://www.sellcell.com/razer/';

        $nokiaPhones = 'https://www.sellcell.com/nokia/';

        $asusPhones = 'https://www.sellcell.com/asus/';


        $iphonesCrawler= $client->request('GET', $iphonesPoint);

        $samsungCrawler= $client->request('GET', $samsungPoint);

        $htcCrawler = $client->request('GET', $htcPhones);

        $motorolaCrawler = $client->request('GET', $motorolaPhones);

        $lgCrawler = $client->request('GET', $lgPhones);

        $onePlusCrawler = $client->request('GET', $onePlusPhones);

        $googleCrawler = $client->request('GET', $googlePhones);

        $sonyCrawler = $client->request('GET', $sonyPhones);

        $blackBerryCrawler = $client->request('GET', $blackBerryPhones);

        $huaweiCrawler = $client->request('GET', $huaweiPhones);

        $kyoceraCrawler = $client->request('GET', $kyoceraPhones);

        $zteCrawler = $client->request('GET', $ztePhones);

        $xiaomiCrawler = $client->request('GET', $xiaomiPhones);

        $razerCrawler = $client->request('GET', $razerPhones);

        $nokiaCrawler = $client->request('GET', $nokiaPhones);

        $asusCrawler = $client->request('GET', $asusPhones);

        //PULL IPHONE
        $iphones = $iphonesCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $iphoneStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $iphoneStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
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

        $iphoneCategory = Category::query()->create([
            'name' => 'Sell iPhone',
            'image' => $phoneImage,
        ]);

        foreach ($iphones as $iphone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($iphone['capacities']))->first();

            if (!$capacityStep && $iphone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $iphone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $iphoneCategory->getKey(),
                'name' => $iphone['title'],
                'slug' => $iphone['slug'],
                'custom_text' => $iphone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($iphone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Iphones was parsed...');

        //PULL SAMSUNG
        $samsungPhones = $samsungCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {
            $samsungStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));


            $capacities = $samsungStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
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

        $samsungCategory = Category::query()->create([
            'name' => 'Sell Samsung Phone',
            'image' => $phoneImage,
        ]);

        foreach ($samsungPhones as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $samsungCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Samsung phones was parsed...');

        $androidCategory = Category::query()->create([
            'name' => 'Sell Android',
            'image' => $phoneImage,
        ]);

        //PULL HTC
        $htc = $htcCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $htcStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $htcStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'HTC '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/htc-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $htcCategory = Category::query()->create([
            'name' => 'Sell HTC Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($htc as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $htcCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('HTC Phones was parsed...');

        //PULL MOTOROLA
        $motorola = $motorolaCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $motorolaStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $motorolaStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Motorola '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/motorola-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $motorolaCategory = Category::query()->create([
            'name' => 'Sell MOTOROLA Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($motorola as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $motorolaCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('MOTOROLA Phones was parsed...');

        //PULL LG
        $lg = $lgCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $lgStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $lgStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
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

        $lgCategory = Category::query()->create([
            'name' => 'Sell LG Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($lg as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $lgCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('LG Phones was parsed...');

        //PULL ONE+
        $onePlus = $onePlusCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $onePlusStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $onePlusStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'OnePlus '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/oneplus-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $oneplusCategory = Category::query()->create([
            'name' => 'Sell OnePlus Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($onePlus as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $oneplusCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('OnePlus Phones was parsed...');

        //PULL GOOGLE phones
        $google = $googleCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $googleStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $googleStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Google '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/google-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $googleCategory = Category::query()->create([
            'name' => 'Sell Google Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($google as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $googleCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Google Phones was parsed...');

        //PULL Sony phones
        $sony = $sonyCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $sonyStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $sonyStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Sony '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/sony-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $sonyCategory = Category::query()->create([
            'name' => 'Sell Sony Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($sony as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $sonyCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Sony Phones was parsed...');

        //PULL BlackBerry phones
        $blackBerry = $blackBerryCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $blackBerryStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $blackBerryStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'BlackBerry '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/blackberry-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $blackBerryCategory = Category::query()->create([
            'name' => 'Sell BlackBerry Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($blackBerry as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $blackBerryCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('BlackBerry Phones was parsed...');

        //PULL Huawei phones
        $huawei = $huaweiCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $huaweiStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $huaweiStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Huawei '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/huawei-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $huaweiCategory = Category::query()->create([
            'name' => 'Sell Huawei Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($huawei as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $huaweiCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Huawei Phones was parsed...');

        //PULL Kyocera phones
        $kyocera = $kyoceraCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $kyoceraStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $kyoceraStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Kyocera '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/kyocera-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $kyoceraCategory = Category::query()->create([
            'name' => 'Sell Kyocera Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($kyocera as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $kyoceraCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Kyocera Phones was parsed...');

        //PULL ZTE phones
        $zte = $zteCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {
            $zteStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $zteStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'ZTE '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/zte-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $zteCategory = Category::query()->create([
            'name' => 'Sell ZTE Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($zte as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $zteCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('ZTE Phones was parsed...');

        //PULL Xiaomi phones
        $xiaomi = $xiaomiCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $xiaomiStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $xiaomiStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Xiaomi '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/xiaomi-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $xiaomiCategory = Category::query()->create([
            'name' => 'Sell Xiaomi Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($xiaomi as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $xiaomiCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Xiaomi Phones was parsed...');

        //PULL Razer phones
        $razer = $razerCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $razerStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $razerStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Razer '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/razer-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $razerCategory = Category::query()->create([
            'name' => 'Sell Razer Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($razer as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $razerCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Razer Phones was parsed...');

        //PULL Nokia phones
        $nokia = $nokiaCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $nokiaStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $nokiaStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Nokia '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/nokia-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $nokiaCategory = Category::query()->create([
            'name' => 'Sell Nokia Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($nokia as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $nokiaCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Nokia Phones was parsed...');

        //PULL Asus phones
        $asus = $asusCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $asusStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $asusStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Asus '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/asus-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $asusCategory = Category::query()->create([
            'name' => 'Sell Asus Phones',
            'image' => $phoneImage,
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($asus as $phone) {
            $capacityStep = Step::query()->whereRaw('items = cast(? as json)', json_encode($phone['capacities']))->first();

            if (!$capacityStep && $phone['capacities'] != []) {
                $capacityStep = Step::query()->create([
                    'name' => 'What is the phone\'s storage capacity?',
                    'items' => $phone['capacities'],
                ]);
            }

            $category = Category::query()->create([
                'subcategory_id' => $asusCategory->getKey(),
                'name' => $phone['title'],
                'slug' => $phone['slug'],
                'custom_text' => $phone['price'],
                'is_parsed' => 1,
            ]);

            $category->steps()->attach(Step::find([$conditionStep->getKey(), $carrierStep->getKey(), $capacityStep ? $capacityStep->getKey() : 0]));

            $media = $category->addMediaFromUrl($phone['image'])
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }

        $this->line('Asus Phones was parsed...');

        $this->line('Work is done!');
    }
}
