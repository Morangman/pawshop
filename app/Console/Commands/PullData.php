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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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
    protected $description = 'Parse devices from https://www.sellcell.com/';

    public $conditions = [];
    public $carriers = [];
    public $phoneAccessories = [];
    public $consoleAccessories = [];
    public $smartwatchAccessories = [];
    public $appleTVAccesories = [];
    public $smartwatchBands = [];
    public $functionals = [];
    public $controller = [];
    public $conditionName;
    public $carrierName;
    public $phoneAccessoriesName;
    public $consoleAccessoriesName;
    public $smartwatchAccessoriesName;
    public $appleTVAccesoriesName;
    public $functionalName;
    public $capacityName;
    public $controllerName;
    public $bandTypeName;

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

        DB::table('step_names')->truncate();
        DB::table('steps')->truncate();
        DB::table('category_step')->truncate();
        DB::table('prices')->truncate();
        DB::table('categories')->truncate();
        DB::table('premium_price')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->conditionName = StepName::query()->create([
            'name' => 'Condition',
            'title' => 'What is the condition of the device?',
            'is_condition' => 1,
            'is_functional' => 0,
            'is_checkbox' => 0,
        ]);

        $conditionsArr = [
            [
                'name' => 'Brand New',
                'attribute' => 'condition',
                'text' => 'Phone still in factory original packaging. Must come with the box and all accessories sealed/untouched.',
                'slug' => 'new',
            ],
            [
                'name' => 'Flawless',
                'attribute' => 'condition',
                'text' => 'Has absolutely no scratches, scuffs or other marks. Looks brand new.',
                'slug' => 'new',
            ],
            [
                'name' => 'Good',
                'attribute' => 'condition',
                'text' => 'Shows light to moderate signs of wear. Contains few light scratches and/or dents.',
                'slug' => 'working',
            ],
            [
                'name' => 'Fair',
                'attribute' => 'condition',
                'text' => 'Shows moderate to excessive signs of wear. Contains excessive scratching, major dents, and/or mild housing damage such as a slightly bent frame.',
                'slug' => 'poor',
            ],
            [
                'name' => 'Broken',
                'attribute' => 'condition',
                'text' => 'Cracks (regardless of size) or broken parts on either screen or body of the item.',
                'slug' => 'broken',
            ],
        ];

        foreach ($conditionsArr as $condition) {
            Step::query()->create([
                'name_id' => $this->conditionName->getKey(),
                'slug' => isset($condition['slug']) ? $condition['slug'] : null,
                'attribute' => $condition['attribute'],
                'value' => $condition['name'],
                'decryption' => $condition['text'],
            ]);
        }

        $this->controllerName = StepName::query()->create([
            'name' => 'Controller',
            'title' => 'Is a controller included?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 0,
        ]);

        $controllerVariations = [
            [
                'name' => 'Yes',
            ],
            [
                'name' => 'No',
            ],
        ];

        foreach ($controllerVariations as $controllerVariant) {
            Step::query()->create([
                'name_id' => $this->controllerName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $controllerVariant['name'],
                'decryption' => null,
            ]);
        }

        $this->appleTVAccesoriesName = StepName::query()->create([
            'name' => 'Accessories for Apple TV',
            'title' => 'What accessories will be included?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 1,
        ]);

        $appleTVAccesoriesVariations = [
            [
                'name' => 'Original Box',
                'price_plus' => '1',
            ],
            [
                'name' => 'HDMI/Power Cord',
                'price_plus' => '1',
            ],
            [
                'name' => 'Apple TV Siri Remote',
                'price_plus' => '1',
            ],
        ];

        foreach ($appleTVAccesoriesVariations as $appleTVAccesoriesVariat) {
            Step::query()->create([
                'name_id' => $this->appleTVAccesoriesName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $appleTVAccesoriesVariat['name'],
                'decryption' => null,
            ]);
        }

        $this->consoleAccessoriesName = StepName::query()->create([
            'name' => 'Accessories for gaming console',
            'title' => 'What accessories will be included?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 1,
        ]);

        $consoleAccessoriesArr = [
            [
                'name' => 'Original Box',
                'price_plus' => '1',
            ],
            [
                'name' => 'HDMI/Power Cord',
                'price_plus' => '1',
            ],
        ];

        foreach ($consoleAccessoriesArr as $consoleAccessory) {
            Step::query()->create([
                'name_id' => $this->consoleAccessoriesName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $consoleAccessory['name'],
                'decryption' => null,
            ]);
        }

        $this->smartwatchAccessoriesName = StepName::query()->create([
            'name' => 'Accessories for smartwatch',
            'title' => 'What accessories will be included?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 1,
        ]);

        $smartwatchAccessoriesArr = [
            [
                'name' => 'Original Box',
                'price_plus' => '1',
            ],
            [
                'name' => 'Charging Cable + Adapter',
                'price_plus' => '1',
            ],
        ];

        foreach ($smartwatchAccessoriesArr as $smartwatchAccessory) {
            Step::query()->create([
                'name_id' => $this->smartwatchAccessoriesName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $smartwatchAccessory['name'],
                'decryption' => null,
            ]);
        }

        $this->bandTypeName = StepName::query()->create([
            'name' => 'Smartwatch band type',
            'title' => 'Select your band type:',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 0,
        ]);

        $bandTypesArr = [
            [
                'name' => 'Solo Loop',
            ],
            [
                'name' => 'Sport Band',
            ],
            [
                'name' => 'Sport Loop',
            ],
            [
                'name' => 'Braided Solo Loop',
            ],
            [
                'name' => 'Leather Link',
            ],
            [
                'name' => 'Milanese Loop',
            ],
            [
                'name' => 'Modern Buckle',
            ],
            [
                'name' => 'Link Bracelet',
            ],
        ];

        foreach ($bandTypesArr as $band) {
            Step::query()->create([
                'name_id' => $this->bandTypeName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $band['name'],
                'decryption' => null,
            ]);
        }

        $this->carrierName = StepName::query()->create([
            'name' => 'Carrier',
            'title' => 'Please select the device\'s carrier',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 0,
        ]);

        $carrierArr = [
            [
                'name' => 'Verizon',
                'attribute' => 'network',
                'slug' => '59',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/59.png',
            ],
            [
                'name' => 'AT&T',
                'attribute' => 'network',
                'slug' => '48',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/48.png',
            ],
            [
                'name' => 'T-Mobile',
                'attribute' => 'network',
                'slug' => '30',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/30.png',
            ],
            [
                'name' => 'Sprint',
                'attribute' => 'network',
                'slug' => '49',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/49.png',
            ],
            [
                'name' => 'Other',
                'attribute' => 'network',
                'slug' => '104',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/104.png',
            ],
            [
                'name' => 'Unlocked',
                'attribute' => 'network',
                'slug' => '29',
                'image' => 'https://www.sellcell.com/assets/images/comparison/network-logos/large/29.png',
            ],
        ];

        foreach ($carrierArr as $carrier) {
            Step::query()->create([
                'name_id' => $this->carrierName->getKey(),
                'slug' => isset($carrier['slug']) ? $carrier['slug'] : null,
                'attribute' => $carrier['attribute'],
                'value' => $carrier['name'],
                'decryption' => null,
            ]);
        }

        $this->phoneAccessoriesName = StepName::query()->create([
            'name' => 'Accessories for phones',
            'title' => 'What accessories will be included?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 1,
        ]);

        $accessoriesArr = [
            [
                'name' => 'Original Box',
                'price_plus' => '1',
            ],
            [
                'name' => 'Powercable',
                'price_plus' => '1',
            ],
            [
                'name' => 'Adapter',
                'price_plus' => '1',
            ],
            [
                'name' => 'New Original Headsets',
                'price_plus' => '1',
            ],
        ];

        foreach ($accessoriesArr as $accessories) {
            Step::query()->create([
                'name_id' => $this->phoneAccessoriesName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $accessories['name'],
                'decryption' => null,
            ]);
        }

        $this->functionalName = StepName::query()->create([
            'name' => 'Functional',
            'title' => 'Is the device fully functional?',
            'is_condition' => 0,
            'is_functional' => 1,
            'is_checkbox' => 0,
        ]);

        $functionalArr = [
            [
                'name' => 'Yes',
                'text' => 'Switches on and functions 100% as intended.',
            ],
            [
                'name' => 'No',
                'text' => 'Does not switch on and/or is not fully functional.',
            ],
        ];

        foreach ($functionalArr as $functional) {
            Step::query()->create([
                'name_id' => $this->functionalName->getKey(),
                'slug' => null,
                'attribute' => null,
                'value' => $functional['name'],
                'decryption' => $functional['text'],
            ]);
        }

        $this->capacityName = StepName::query()->create([
            'name' => 'Capacity',
            'title' => 'What is the device\'s storage capacity?',
            'is_condition' => 0,
            'is_functional' => 0,
            'is_checkbox' => 0,
        ]);

        $this->conditions = Step::query()->where('name_id', $this->conditionName->getKey())->get();
        $this->carriers = Step::query()->where('name_id', $this->carrierName->getKey())->get();
        $this->phoneAccessories = Step::query()->where('name_id', $this->phoneAccessoriesName->getKey())->get();
        $this->functionals = Step::query()->where('name_id', $this->functionalName->getKey())->get();
        $this->controller = Step::query()->where('name_id', $this->controllerName->getKey())->get();
        $this->consoleAccessories = Step::query()->where('name_id', $this->consoleAccessoriesName->getKey())->get();
        $this->smartwatchAccessories = Step::query()->where('name_id', $this->smartwatchAccessoriesName->getKey())->get();
        $this->appleTVAccesories = Step::query()->where('name_id', $this->appleTVAccesoriesName->getKey())->get();
        $this->smartwatchBands = Step::query()->where('name_id', $this->bandTypeName->getKey())->get();

        $this->line('Start parsing devices...');

        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        $phoneImage = '/client/images/phone.png';
        $tabletImage = '/client/images/tablet.png';
        $ipodImage = '/client/images/ipod.png';
        $cameraImage = '/client/images/camera.png';
        $consoleImage = '/client/images/console.png';
        $watchImage = '/client/images/watch.png';
        $appleTvImage = '/client/images/appletv.png';

        $maxPrice = 5;

        $settings = Setting::latest('updated_at')->first();

        $basePath = $settings->getAttribute('general_settings')['base_path'];

        $iphonesPoint = $settings->getAttribute('general_settings')['iphones_point'];

        $samsungPoint = $settings->getAttribute('general_settings')['samsung_point'];

        $htcPhones = $settings->getAttribute('general_settings')['htc_phones'];

        $motorolaPhones = $settings->getAttribute('general_settings')['motorola_phones'];

        $lgPhones = $settings->getAttribute('general_settings')['lg_phones'];

        $onePlusPhones = $settings->getAttribute('general_settings')['onePlus_phones'];

        $googlePhones = $settings->getAttribute('general_settings')['google_phones'];

        $sonyPhones = $settings->getAttribute('general_settings')['sony_phones'];

        $blackBerryPhones = $settings->getAttribute('general_settings')['blackBerry_phones'];

        $huaweiPhones = $settings->getAttribute('general_settings')['huawei_phones'];

        $kyoceraPhones = $settings->getAttribute('general_settings')['kyocera_phones'];

        $ztePhones = $settings->getAttribute('general_settings')['zte_phones'];

        $xiaomiPhones = $settings->getAttribute('general_settings')['xiaomi_phones'];

        $razerPhones = $settings->getAttribute('general_settings')['razer_phones'];

        $nokiaPhones = $settings->getAttribute('general_settings')['nokia_phones'];

        $asusPhones = $settings->getAttribute('general_settings')['asus_phones'];


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

        $iphoneCategory = Category::query()->create([
            'name' => 'Sell iPhone',
            'slug' => 'sell_iphone',
            'image' => $phoneImage,
        ]);

        foreach ($iphones as $iphone) {

            if ($iphone['capacities'] != []) {
                foreach ($iphone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
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

                $this->savePhoneSteps($category->getKey(), $iphone['capacities']);

                $media = $category->addMediaFromUrl($iphone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

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

        $samsungCategory = Category::query()->create([
            'name' => 'Sell Samsung Phone',
            'slug' => 'sell_samsung_phone',
            'image' => $phoneImage,
        ]);

        foreach ($samsungPhones as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $samsungCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Samsung phones was parsed...');

        $androidCategory = Category::query()->create([
            'name' => 'Sell Android',
            'slug' => 'sell_android',
            'image' => $phoneImage,
        ]);

        //PULL HTC
        $htc = $htcCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $htcStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $htcStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell HTC Phone',
            'image' => $phoneImage,
            'slug' => 'sell_htc_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($htc as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $htcCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('HTC Phones was parsed...');

        //PULL MOTOROLA
        $motorola = $motorolaCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $motorolaStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $motorolaStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell MOTOROLA Phone',
            'image' => $phoneImage,
            'slug' => 'sell_motorola_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($motorola as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $motorolaCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('MOTOROLA Phones was parsed...');

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

        $lgCategory = Category::query()->create([
            'name' => 'Sell LG Phone',
            'image' => $phoneImage,
            'slug' => 'sell_lg_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($lg as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $lgCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('LG Phones was parsed...');

        //PULL ONE+
        $onePlus = $onePlusCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $onePlusStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $onePlusStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell OnePlus Phone',
            'image' => $phoneImage,
            'slug' => 'sell_oneplus_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($onePlus as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $oneplusCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('OnePlus Phones was parsed...');

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

        $googleCategory = Category::query()->create([
            'name' => 'Sell Google Phone',
            'image' => $phoneImage,
            'slug' => 'sell_google_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($google as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $googleCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Google Phones was parsed...');

        //PULL Sony phones
        $sony = $sonyCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $sonyStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $sonyStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Sony Phone',
            'image' => $phoneImage,
            'slug' => 'sell_sony_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($sony as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $sonyCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Sony Phones was parsed...');

        //PULL BlackBerry phones
        $blackBerry = $blackBerryCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $blackBerryStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $blackBerryStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell BlackBerry Phone',
            'image' => $phoneImage,
            'slug' => 'sell_blackberry_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($blackBerry as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $blackBerryCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('BlackBerry Phones was parsed...');

        //PULL Huawei phones
        $huawei = $huaweiCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $huaweiStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $huaweiStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Huawei Phone',
            'image' => $phoneImage,
            'slug' => 'sell_huawei_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($huawei as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $huaweiCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Huawei Phones was parsed...');

        //PULL Kyocera phones
        $kyocera = $kyoceraCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $kyoceraStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $kyoceraStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Kyocera Phone',
            'image' => $phoneImage,
            'slug' => 'sell_kyocera_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($kyocera as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $kyoceraCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Kyocera Phones was parsed...');

        //PULL ZTE phones
        $zte = $zteCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {
            $zteStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $zteStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell ZTE Phone',
            'image' => $phoneImage,
            'slug' => 'sell_zte_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($zte as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $zteCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('ZTE Phones was parsed...');

        //PULL Xiaomi phones
        $xiaomi = $xiaomiCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $xiaomiStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $xiaomiStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Xiaomi Phone',
            'image' => $phoneImage,
            'slug' => 'sell_xiaomi_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($xiaomi as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $xiaomiCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Xiaomi Phones was parsed...');

        //PULL Razer phones
        $razer = $razerCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $razerStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $razerStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Razer Phone',
            'image' => $phoneImage,
            'slug' => 'sell_razer_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($razer as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $razerCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Razer Phones was parsed...');

        //PULL Nokia phones
        $nokia = $nokiaCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $nokiaStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $nokiaStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Nokia Phone',
            'image' => $phoneImage,
            'slug' => 'sell_nokia_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($nokia as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $nokiaCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Nokia Phones was parsed...');

        //PULL Asus phones
        $asus = $asusCrawler->filter('#page-body-popular-devices > .row')->first()->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $asusStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $asusStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
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
            'name' => 'Sell Asus Phone',
            'image' => $phoneImage,
            'slug' => 'sell_asus_phone',
            'subcategory_id' => $androidCategory->getKey(),
        ]);

        foreach ($asus as $phone) {
            if ($phone['capacities'] != []) {
                foreach ($phone['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $phone['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $asusCategory->getKey(),
                    'name' => $phone['title'],
                    'slug' => $phone['slug'],
                    'custom_text' => $phone['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $phone['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->savePhoneSteps($category->getKey(), $phone['capacities']);

                $media = $category->addMediaFromUrl($phone['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Asus Phones was parsed...');

        $this->line('Phones was parsed!');

        //PARSING TABLETS

        $this->line('Starting parsing tablets...');

        $ipadsPoint = $settings->getAttribute('general_settings')['ipads_point'];

        $samsungTabletsPoint = $settings->getAttribute('general_settings')['samsung_tablets_point'];

        $microsoftTabletsPoint = $settings->getAttribute('general_settings')['microsoft_tablets_point'];

        $ipadsCrawler = $client->request('GET', $ipadsPoint);

        $samsungTabletsCrawler = $client->request('GET', $samsungTabletsPoint);

        $microsoftTabletsCrawler = $client->request('GET', $microsoftTabletsPoint);

        $tabletCategory = Category::query()->create([
            'name' => 'Sell Tablet',
            'slug' => 'sell_tablet',
            'image' => $tabletImage,
        ]);

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

        $ipadCategory = Category::query()->create([
            'name' => 'Sell Apple iPad',
            'image' => $tabletImage,
            'slug' => 'sell_apple_ipad_tablet',
            'subcategory_id' => $tabletCategory->getKey(),
        ]);

        foreach ($ipads as $tablet) {
            if ($tablet['networks'] != []) {
                foreach ($tablet['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->carrierName->getKey())
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->carrierName->getKey(),
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            if ($tablet['capacities'] != []) {
                foreach ($tablet['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $tablet['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $ipadCategory->getKey(),
                    'name' => $tablet['title'],
                    'slug' => $tablet['slug'],
                    'custom_text' => $tablet['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $tablet['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveDeviceSteps($category->getKey(), $tablet['capacities'], $tablet['networks']);

                $media = $category->addMediaFromUrl($tablet['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('iPads tablets was parsed...');

        //SAMSUNG TABLETS
        $samsungTablts = $samsungTabletsCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $samsungTabletsStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $samsungTabletsStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            $networks = $samsungTabletsStepsCrawler->filter('.network-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $ntwrks = [];

                return $ntwrks[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'network' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Samsung '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/samsung-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
                'networks' => $networks,
            ];
        });

        $samsungTabletCategory = Category::query()->create([
            'name' => 'Sell Samsung Tablet',
            'image' => $tabletImage,
            'slug' => 'sell_samsung_tablet',
            'subcategory_id' => $tabletCategory->getKey(),
        ]);

        foreach ($samsungTablts as $tablet) {
            if ($tablet['networks'] != []) {
                foreach ($tablet['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->carrierName->getKey())
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->carrierName->getKey(),
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            if ($tablet['capacities'] != []) {
                foreach ($tablet['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $tablet['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $samsungTabletCategory->getKey(),
                    'name' => $tablet['title'],
                    'slug' => $tablet['slug'],
                    'custom_text' => $tablet['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $tablet['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveDeviceSteps($category->getKey(), $tablet['capacities'], $tablet['networks']);

                $media = $category->addMediaFromUrl($tablet['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Samsung tablets was parsed...');

        //MICROSOFT TABLETS
        $microsoftTablets = $microsoftTabletsCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $microsoftTabletsStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $microsoftTabletsStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            $networks = $microsoftTabletsStepsCrawler->filter('.network-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $ntwrks = [];

                return $ntwrks[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'network' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Microsoft '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/microsoft-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
                'networks' => $networks,
            ];
        });

        $microsoftTabletCategory = Category::query()->create([
            'name' => 'Sell Microsoft Surface',
            'image' => $tabletImage,
            'slug' => 'sell_microsoft_tablet',
            'subcategory_id' => $tabletCategory->getKey(),
        ]);

        foreach ($microsoftTablets as $tablet) {
            if ($tablet['networks'] != []) {
                foreach ($tablet['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->carrierName->getKey())
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->carrierName->getKey(),
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            if ($tablet['capacities'] != []) {
                foreach ($tablet['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $tablet['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $microsoftTabletCategory->getKey(),
                    'name' => $tablet['title'],
                    'slug' => $tablet['slug'],
                    'custom_text' => $tablet['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $tablet['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveDeviceSteps($category->getKey(), $tablet['capacities'], $tablet['networks']);

                $media = $category->addMediaFromUrl($tablet['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Microsoft tablets was parsed...');

        $this->line('Tablets was parsed!');

        $this->line('Starting parsing iPods...');

        $iPodsPoint = $settings->getAttribute('general_settings')['iPods_point'];

        $iPodsCrawler = $client->request('GET', $iPodsPoint);

        $iPodsCategory = Category::query()->create([
            'name' => 'Sell Apple iPod',
            'slug' => 'sell_apple_ipod',
            'image' => $ipodImage,
        ]);

        //APPLE IPODS
        $iPods = $iPodsCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $iPodsStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $networks = $iPodsStepsCrawler->filter('.network-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $ntwrks = [];

                return $ntwrks[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'network' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            $capacities = $iPodsStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
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
                'networks' => $networks,
            ];
        });

        foreach ($iPods as $device) {
            if ($device['networks'] != []) {
                foreach ($device['networks'] as $network) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->carrierName->getKey())
                        ->where('slug', $network['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->carrierName->getKey(),
                            'slug' => $network['slug'],
                            'attribute' => $network['attribute'],
                            'value' => $network['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $iPodsCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveDeviceSteps($category->getKey(), $device['capacities'], $device['networks']);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('iPods was parsed...');

        $this->line('Starting parsing GoPro...');

        $goproPoint = $settings->getAttribute('general_settings')['gopro_point'];

        $goproCrawler = $client->request('GET', $goproPoint);

        $goproCategory = Category::query()->create([
            'name' => 'Sell GoPro',
            'slug' => 'sell_gopro',
            'image' => $cameraImage,
        ]);

        //GOPRO ACTION CAMERA
        $gopro = $goproCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $goproStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $goproStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'GoPro '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/gopro-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        foreach ($gopro as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $goproCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveGoProSteps($category->getKey(), $device['capacities']);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('GoPro was parsed...');

        $this->line('Starting parsing gaming consoles...');

        $xboxPoint = $settings->getAttribute('general_settings')['xbox_point'];

        $playstationPoint = $settings->getAttribute('general_settings')['playstation_point'];

        $switchPoint = $settings->getAttribute('general_settings')['switch_point'];

        $dsPoint = $settings->getAttribute('general_settings')['ds_point'];

        $xboxCrawler = $client->request('GET', $xboxPoint);

        $playstationCrawler = $client->request('GET', $playstationPoint);

        $switchCrawler = $client->request('GET', $switchPoint);

        $dsCrawler = $client->request('GET', $dsPoint);

        $consoleCategory = Category::query()->create([
            'name' => 'Sell Game Console',
            'slug' => 'sell_game_console',
            'image' => $consoleImage,
        ]);

        //XBOX CONSOLES
        $xbox = $xboxCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $xboxStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $xboxStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
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
                'slug' =>   preg_replace('/^\/(.+?)\/microsoft-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $xboxCategory = Category::query()->create([
            'name' => 'Sell Xbox',
            'image' => $consoleImage,
            'slug' => 'sell_xbox_console',
            'subcategory_id' => $consoleCategory->getKey(),
        ]);

        foreach ($xbox as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $xboxCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveConsoleSteps($category->getKey(), $device['capacities'], false);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Xbox consoles was parsed...');

        //PLAYSTATION CONSOLES
        $playstation = $playstationCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $playstationStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $playstationStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
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
                'slug' =>   preg_replace('/^\/(.+?)\/sony-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $playstationCategory = Category::query()->create([
            'name' => 'Sell Playstation',
            'image' => $consoleImage,
            'slug' => 'sell_playstation_console',
            'subcategory_id' => $consoleCategory->getKey(),
        ]);

        foreach ($playstation as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $playstationCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveConsoleSteps($category->getKey(), $device['capacities'], false);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Playstation consoles was parsed...');

        //NINTENDO SWITCH
        $switch = $switchCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $switchStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $switchStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Nintendo '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/nintendo-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $switchCategory = Category::query()->create([
            'name' => 'Sell Nintendo Switch',
            'image' => $consoleImage,
            'slug' => 'sell_nintendo_switch_console',
            'subcategory_id' => $consoleCategory->getKey(),
        ]);

        foreach ($switch as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $switchCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveConsoleSteps($category->getKey(), $device['capacities'], true);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Nintendo Switch consoles was parsed...');

        //NINTENDO DS
        $ds = $dsCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $dsStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $dsStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Nintendo '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/nintendo-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        $dsCategory = Category::query()->create([
            'name' => 'Sell Nintendo (3DS / 2DS)',
            'image' => $consoleImage,
            'slug' => 'sell_nintendo_ds_console',
            'subcategory_id' => $consoleCategory->getKey(),
        ]);

        foreach ($ds as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $dsCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveConsoleSteps($category->getKey(), $device['capacities'], true);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Nintendo DS consoles was parsed...');

        $this->line('Gaming consoles was parsed!');

        $this->line('Starting parsing apple watch...');

        $appleWatchPoint = $settings->getAttribute('general_settings')['appleWatch_point'];

        $appleWatchCrawler = $client->request('GET', $appleWatchPoint);

        $watchCategory = Category::query()->create([
            'name' => 'Sell Smartwatch',
            'slug' => 'sell_smartwatch',
            'image' => $watchImage,
        ]);

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


        $appleWatchCategory = Category::query()->create([
            'name' => 'Sell Apple Watch',
            'image' => $watchImage,
            'slug' => 'sell_apple_smartwatch',
            'subcategory_id' => $watchCategory->getKey(),
        ]);

        foreach ($appleWatch as $device) {
            foreach ($device as $key => $items) {
                $appleWatchSubCategory = Category::query()->create([
                    'name' => $key,
                    'image' => $watchImage,
                    'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $key))),
                    'subcategory_id' => $appleWatchCategory->getKey(),
                ]);

                foreach ($items as $item) {
                    if ($item['networks'] != []) {
                        foreach ($item['networks'] as $network) {
                            $isItemExist = Step::query()
                                ->where('name_id', $this->carrierName->getKey())
                                ->where('slug', $network['slug'])
                                ->exists();

                            if (!$isItemExist) {
                                Step::query()->create([
                                    'name_id' => $this->carrierName->getKey(),
                                    'slug' => $network['slug'],
                                    'attribute' => $network['attribute'],
                                    'value' => $network['name'],
                                    'decryption' => null,
                                ]);
                            }
                        }
                    }

                    if ($item['capacities'] != []) {
                        foreach ($item['capacities'] as $capacity) {
                            $isItemExist = Step::query()
                                ->where('name_id', $this->capacityName->getKey())
                                ->where('slug', $capacity['slug'])
                                ->exists();

                            if (!$isItemExist) {
                                Step::query()->create([
                                    'name_id' => $this->capacityName->getKey(),
                                    'slug' => $capacity['slug'],
                                    'attribute' => $capacity['attribute'],
                                    'value' => $capacity['name'],
                                    'decryption' => null,
                                ]);
                            }
                        }
                    }

                    $isExist = Category::query()->where('name', $item['title'])->exists();

                    if (!$isExist) {
                        $category = Category::query()->create([
                            'subcategory_id' => $appleWatchSubCategory->getKey(),
                            'name' => $item['title'],
                            'slug' => $item['slug'],
                            'custom_text' => $item['price'],
                            'is_parsed' => 1,
                            'is_hidden' => $item['price'] < $maxPrice ? 1 : 0,
                        ]);

                        $this->saveSmartwatchSteps($category->getKey(), $item['capacities'], $item['networks']);

                        $media = $category->addMediaFromUrl($item['image'])
                            ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                        $category->update(['image' => $media->getFullUrl()]);
                    }
                }
            }
        }

        $this->line('Apple watch was parsed...');

        $this->line('Starting parsing AppleTV...');

        $appleTvPoint = $settings->getAttribute('general_settings')['appleTV_point'];

        $appleTVCrawler = $client->request('GET', $appleTvPoint);

        $appleTVCategory = Category::query()->create([
            'name' => 'Sell Apple TV',
            'slug' => 'sell_apple_tv',
            'image' => $appleTvImage,
        ]);

        //APPLE TV
        $appleTV = $appleTVCrawler->filter('.device')->each(function (Crawler $nodeCrawler) use ($basePath, $client) {

            $appleTVStepsCrawler = $client->request('GET', $basePath . $nodeCrawler->filter('a')->attr('href'));

            $capacities = $appleTVStepsCrawler->filter('.capacity-logos-sprite')->each(function (Crawler $nodeCrawler) {
                $cpies = [];

                return $cpies[] = [
                    'slug' => $slug = $nodeCrawler->filter('.do_ajax')->attr('value'),
                    'attribute' => $slug ? 'capacity' : null,
                    'name' => $nodeCrawler->filter('.do_ajax')->attr('data-attribute'),
                ];
            });

            return [
                'image' => $basePath . $nodeCrawler->filter('img')->eq(1)->attr('src'),
                'title' => 'Apple '.$nodeCrawler->filter('.h4')->text(),
                'slug' =>   preg_replace('/^\/(.+?)\/apple-(.+?)\/$/', "$2", $nodeCrawler->filter('a')->attr('href')),
                'price' => trim($nodeCrawler->filter('.price')->text(), '$'),
                'capacities' => $capacities,
            ];
        });

        foreach ($appleTV as $device) {
            if ($device['capacities'] != []) {
                foreach ($device['capacities'] as $capacity) {
                    $isItemExist = Step::query()
                        ->where('name_id', $this->capacityName->getKey())
                        ->where('slug', $capacity['slug'])
                        ->exists();

                    if (!$isItemExist) {
                        Step::query()->create([
                            'name_id' => $this->capacityName->getKey(),
                            'slug' => $capacity['slug'],
                            'attribute' => $capacity['attribute'],
                            'value' => $capacity['name'],
                            'decryption' => null,
                        ]);
                    }
                }
            }

            $isExist = Category::query()->where('name', $device['title'])->exists();

            if (!$isExist) {
                $category = Category::query()->create([
                    'subcategory_id' => $appleTVCategory->getKey(),
                    'name' => $device['title'],
                    'slug' => $device['slug'],
                    'custom_text' => $device['price'],
                    'is_parsed' => 1,
                    'is_hidden' => $device['price'] < $maxPrice ? 1 : 0,
                ]);

                $this->saveAppleTVSteps($category->getKey(), $device['capacities']);

                $media = $category->addMediaFromUrl($device['image'])
                    ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

                $category->update(['image' => $media->getFullUrl()]);
            }
        }

        $this->line('Apple TV was parsed...');

        $this->line('Work is done!');
    }

    /**
     * @param int $categoryId
     * @param array $capacities
     * @param array $networks
     *
     * @return void
     */
    private function savePhoneSteps(int $categoryId, array $capacities = [], array $networks = [])
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($networks) {
            foreach ($networks as $nt) {
                $netwk = Step::query()
                    ->where('name_id', $this->carrierName->getKey())
                    ->where('slug', $nt['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $netwk->getKey(),
                    'sort_order' => 2,
                ]);
            }
        } else {
            foreach ($this->carriers as $cr) {
                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cr->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 3,
                ]);
            }
        }

        foreach ($this->phoneAccessories as $ac) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'price_plus' => 1,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'sort_order' => 4,
            ]);
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 5,
            ]);
        }
    }

    /**
     * @param int $categoryId
     * @param array $capacities
     * @param array $networks
     *
     * @return void
     */
    private function saveDeviceSteps(int $categoryId, array $capacities = [], array $networks = [])
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($networks) {
            foreach ($networks as $nt) {
                $netwk = Step::query()
                    ->where('name_id', $this->carrierName->getKey())
                    ->where('slug', $nt['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $netwk->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 3,
                ]);
            }
        }

        foreach ($this->phoneAccessories as $ac) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'price_plus' => 1,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'sort_order' => 4,
            ]);
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 5,
            ]);
        }
    }

    /**
     * @param int $categoryId
     * @param array $capacities
     *
     * @return void
     */
    private function saveGoProSteps(int $categoryId, array $capacities = [])
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 3,
            ]);
        }
    }

        /**
     * @param int $categoryId
     * @param array $capacities
     *
     * @return void
     */
    private function saveAppleTVSteps(int $categoryId, array $capacities = [])
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        foreach ($this->appleTVAccesories as $ac) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'price_plus' => 1,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'sort_order' => 3,
            ]);
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 4,
            ]);
        }
    }

    /**
     * @param int $categoryId
     * @param array $capacities
     * @param bool $switch
     *
     * @return void
     */
    private function saveConsoleSteps(int $categoryId, array $capacities = [], bool $switch = false)
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        if (!$switch) {
            foreach ($this->controller as $contrl) {
                if ($contrl->getAttribute('value') === 'Yes') {
                    DB::table('premium_price')->insert([
                        'category_id' => $categoryId,
                        'step_id' => $contrl->getKey(),
                        'price_plus' => 30,
                    ]);
                }

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $contrl->getKey(),
                    'sort_order' => 3,
                ]);
            }
        }

        foreach ($this->consoleAccessories as $ac) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'price_plus' => 1,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'sort_order' => 4,
            ]);
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 5,
            ]);
        }
    }

    /**
     * @param int $categoryId
     * @param array $capacities
     * @param array $networks
     *
     * @return void
     */
    private function saveSmartwatchSteps(int $categoryId, array $capacities = [], array $networks = [])
    {
        foreach ($this->conditions as $c) {
            if ($c->getAttribute('value') === 'Brand New') {
                DB::table('premium_price')->insert([
                    'category_id' => $categoryId,
                    'step_id' => $c->getKey(),
                    'price_percent' => 10,
                ]);
            }

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $c->getKey(),
                'sort_order' => 1,
            ]);
        }

        if ($networks) {
            foreach ($networks as $nt) {
                $netwk = Step::query()
                    ->where('name_id', $this->carrierName->getKey())
                    ->where('slug', $nt['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $netwk->getKey(),
                    'sort_order' => 2,
                ]);
            }
        }

        if ($capacities) {
            foreach ($capacities as $cp) {
                $cpst = Step::query()
                    ->where('name_id', $this->capacityName->getKey())
                    ->where('slug', $cp['slug'])
                    ->first();

                CategoryStep::query()->create([
                    'category_id' => $categoryId,
                    'step_id' => $cpst->getKey(),
                    'sort_order' => 3,
                ]);
            }
        }

        foreach ($this->smartwatchAccessories as $ac) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'price_plus' => 1,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $ac->getKey(),
                'sort_order' => 4,
            ]);
        }

        foreach ($this->smartwatchBands as $band) {
            DB::table('premium_price')->insert([
                'category_id' => $categoryId,
                'step_id' => $band->getKey(),
                'price_plus' => 35,
            ]);

            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $band->getKey(),
                'sort_order' => 5,
            ]);
        }

        foreach ($this->functionals as $func) {
            CategoryStep::query()->create([
                'category_id' => $categoryId,
                'step_id' => $func->getKey(),
                'sort_order' => 6,
            ]);
        }
    }
}
