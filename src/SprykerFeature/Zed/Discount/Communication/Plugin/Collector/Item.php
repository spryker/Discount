<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Item extends AbstractDiscountPlugin implements DiscountCollectorPluginInterface
{

    /**
     * @param DiscountTransfer $discount
     * @param CalculableInterface $container
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface[]
     */
    public function collect(
        DiscountTransfer $discount,
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->getDiscountFacade()
            ->getDiscountableItems($container, $discountCollectorTransfer);
    }

}
