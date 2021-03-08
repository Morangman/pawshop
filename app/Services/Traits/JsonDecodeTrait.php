<?php

declare(strict_types = 1);

namespace App\Services\Traits;

use function json_decode, json_last_error, trim;

use const JSON_ERROR_NONE, true;

/**
 * Trait JsonDecodeTrait
 *
 * @package App\Services\Traits
 */
trait JsonDecodeTrait
{
    /**
     * @param string|null $response
     *
     * @return array
     */
    protected function decodeResult(?string $response): array
    {
        if (($response = trim($response ?? '')) === '') {
            return [];
        }

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $result;
    }
}
