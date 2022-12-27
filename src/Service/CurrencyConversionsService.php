<?php

namespace App\Service;

use App\Entity\CurrencyConversions;
use App\Repository\CurrencyConversionsRepository;

class CurrencyConversionsService
{
    private CurrencyConversionsRepository $conversionsRepository;

    public function __construct(CurrencyConversionsRepository $conversionsRepository)
    {
        $this->conversionsRepository = $conversionsRepository;
    }

    /**
     * @return array|bool
     *
     * Returns today's conversion rates or false if no rates saved for current date
     */
    public function getCurrentRates(): CurrencyConversions|bool
    {
        $rates = $this->conversionsRepository->findOneByDate(date('Y-m-d'));
        return $rates != null ? $rates : false;
    }

    public function saveRates(CurrencyConversions $currencyConversions): void
    {
        $this->conversionsRepository->save($currencyConversions, true);
    }
}