<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class FixedPlugin extends AbstractCalculatorPlugin
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer)
    {
        return $this->getFacade()->calculateFixedDiscount($discountableItems, $discountTransfer);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getFactory()->getMoneyFacade();
    }

    /**
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getMoneyPlugin()->convertDecimalToInteger((float)$value);
    }

    /**
     * @api
     *
     * @param int $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        $moneyTransfer = $this->getMoneyPlugin()->fromInteger((int)$value);

        return $this->getMoneyPlugin()->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @api
     *
     * @param int $amount
     *
     * @return string
     */
    public function getFormattedAmount($amount)
    {
        $moneyTransfer = $this->getMoneyPlugin()->fromInteger($amount);

        return $this->getMoneyPlugin()->formatWithSymbol($moneyTransfer);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAmountValidators()
    {
        return [
            new Regex([
                'pattern' => '/[0-9\.\,]+/',
            ]),
        ];
    }

}
