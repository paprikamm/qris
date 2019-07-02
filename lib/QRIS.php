<?php

namespace Paprika;

use Paprika\Payload\Payload;

/**
 * This class is used as a convenient class that's easy to use
 *
 * Class QRIS
 * @package Paprika
 */
class QRIS
{
    const METHOD_STATIC = 'static';
    const METHOD_DYNAMIC = 'dynamic';

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $merchantID;

    /**
     * Merchant category code
     *
     * @var string
     */
    public $mcc;

    /**
     * Currency
     *
     * @var string
     */
    public $currency;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var float
     */
    public $convenienceAmount;

    /**
     * @var float
     */
    public $conveniencePercentage;

    /**
     * @var int
     */
    public $countryCode;

    /**
     * @var string
     */
    public $merchantName;

    /**
     * @var string
     */
    public $merchantCity;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var Payload[]
     */
    public $payloads = [];
}
