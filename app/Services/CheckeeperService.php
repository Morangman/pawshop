<?php

declare(strict_types = 1);

namespace App\Services;

use App\Services\Traits\JsonDecodeTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

/**
 * Class CheckeeperService
 *
 * @package App\Services
 */
class CheckeeperService
{
    use JsonDecodeTrait;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * CheckeeperService constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $config = Config::get('services.checkeeper');

        $this->token = Arr::get($config, 'token');
        $this->secret = Arr::get($config, 'secret');

        $httpConfig = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        $this->client = new Client($httpConfig);
    }

    /**
     * @return array
     */
    public function createCheck(): array
    {
        $data = [
            'token' => $this->token,
            "test" => '1',
            "date" => "2020-06-14",
            "check_number" => '372',
            "amount" => "5,320.00",
            "memo" => "Widget supply order",
            "bank_routing" => "012345678",
            "bank_account" => "938763720122",
            "payer" => [
                "name" => "Widgets Inc.",
                "address" => [
                    "line1" => "827 Random Street",
                    "line2" => "Suite 102"
                ],
                "city" => "Anytown",
                "state" => "NY",
                "zip" => "14850",
                "signer" => "John Hancock",
            ],
            "payee" => [
                "name" => "Bob's Supplies",
                "address" => [
                  "line1" => "114 Project Lane",
                  "line2" => "Apartment 107",
                ],
                "city" => "Tinkertown",
                "state" => "CA",
                "zip" => "90210",
                "country" => "US"
            ],
            "return_pdf" => true
        ];

        $request_array = json_decode(json_encode($data), true);

        ksort($request_array);

        $query_string = http_build_query($request_array);

        $raw_signature = hash_hmac(
            'sha256', 
            $query_string, 
            $this->secret, 
            TRUE
        );

        $signature = base64_encode($raw_signature);

        $request_array['signature'] = $signature;

        //dd($request_array);

        $response = $this->client
            ->request(
                'POST',
                'https://my.checkeeper.com/api/v2/check/create/',
                [
                    'json' => $request_array
                ]
            )
            ->getBody()
            ->getContents();

        $result = $this->decodeResult($response);

        return $result;
    }

}