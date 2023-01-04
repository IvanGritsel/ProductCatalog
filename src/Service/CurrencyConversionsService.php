<?php

namespace App\Service;

use App\Entity\CurrencyConversions;
use App\Repository\CurrencyConversionsRepository;
use SimpleXMLElement;

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

    public function saveRates(SimpleXMLElement $parsedXml): CurrencyConversions
    {
        $conversionsArray = [];
        $conversionsArray['USD'] = preg_replace('/(buy=\")|(")/', '', $parsedXml->filials->filial[0]->rates->value[0]['buy']->asXML());
        $conversionsArray['EUR'] = preg_replace('/(buy=\")|(")/', '', $parsedXml->filials->filial[0]->rates->value[1]['buy']->asXML());
        $conversionsArray['RUB'] = preg_replace('/(buy=\")|(")/', '', $parsedXml->filials->filial[0]->rates->value[2]['buy']->asXML());
        $conversionsEntity = new CurrencyConversions();
        $conversionsEntity->setRates($conversionsArray);
        $conversionsEntity->setDate(new DateTime(\date('Y-m-d')));
        $this->conversionsRepository->save($conversionsEntity, true);
        return $conversionsEntity;
    }
}