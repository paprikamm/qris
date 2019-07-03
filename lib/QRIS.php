<?php

namespace Paprika;

use Paprika\Payload\PayloadRoot;

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

    const CONVENIENCE_TYPE_NONE = 'none';
    const CONVENIENCE_TYPE_FIXED = 'fixed';
    const CONVENIENCE_TYPE_PERCENTAGE = 'percentage';
    const CONVENIENCE_TYPE_BOTH = 'both';

    /**
     * @var string
     */
    public $method;

    /**
     * @var MerchantAccountInformation[]
     */
    public $merchantAccounts = [];

    /**
     * @var AdditionalField[]
     */
    public $additionalFields = [];

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
    public $amount = 0;

    /**
     * @var string
     */
    public $convenienceType;

    /**
     * @var float
     */
    public $convenienceAmount = 0;

    /**
     * @var float
     */
    public $conveniencePercentage = 0;

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
     * @var PayloadRoot
     */
    public $payloadRoot;
}
