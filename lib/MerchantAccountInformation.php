<?php

namespace Paprika;

class MerchantAccountInformation
{
    const TYPE_VISA = 'visa';
    const TYPE_MASTERCARD = 'mastercard';
    const TYPE_EMVCO = 'emvco';
    const TYPE_DISCOVER = 'discover';
    const TYPE_AMEX = 'amex';
    const TYPE_JCB = 'jcb';
    const TYPE_UNION_PAY = 'union_pay';
    const TYPE_DOMESTIC = 'domestic';
    const TYPE_CENTRAL_REPOSITORY = 'central_repository';

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $uniqueID;

    /**
     * @var string
     */
    public $merchantPAN;

    /**
     * @var string
     */
    public $merchantID;

    /**
     * @var string
     */
    public $merchantCriteria;
}
