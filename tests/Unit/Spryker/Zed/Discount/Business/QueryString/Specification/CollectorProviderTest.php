<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;

class CollectorProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testCreateAndShouldReturnInstanceOfAndCompositeSpecification()
    {
        $collectorProvider = $this->createCollectorProvider();
        $compositeSpecification = $collectorProvider->createAnd(
            $this->createCollectorSpecificationMock(),
            $this->createCollectorSpecificationMock()
        );

        $this->assertInstanceOf(CollectorAndSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testCreateOrShouldReturnInstanceOfOrCompositeSpecification()
    {
        $collectorProvider = $this->createCollectorProvider();
        $compositeSpecification = $collectorProvider->createOr(
            $this->createCollectorSpecificationMock(),
            $this->createCollectorSpecificationMock()
        );

        $this->assertInstanceOf(CollectorOrSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldReturnContextWithClauseAndPlugin()
    {
        $decisionRulePluginMock = $this->createCollectorPluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('sku');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createCollectorProvider($decisionRulePluginMock);
        $decisionRuleSpecificationContext = $decisionRuleProvider->getSpecificationContext($clauseTransfer);

        $this->assertInstanceOf(CollectorContext::class, $decisionRuleSpecificationContext);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldThrowExceptionWhenSpecificationNotFound()
    {
        $this->expectException(QueryStringException::class);

        $decisionRulePluginMock = $this->createCollectorPluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('does not exists');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createCollectorProvider($decisionRulePluginMock);
        $decisionRuleProvider->getSpecificationContext($clauseTransfer);
    }

    /**
     * @param $collectorPluginMock
     *
     * @return CollectorProvider
     */
    protected function createCollectorProvider($collectorPluginMock = null)
    {
        if ($collectorPluginMock === null) {
            $collectorPluginMock = $this->createCollectorPluginMock();
        }

        return new CollectorProvider([$collectorPluginMock]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectorPluginInterface
     */
    protected function createCollectorPluginMock()
    {
        return $this->getMock(CollectorPluginInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectorSpecificationInterface
     */
    protected function createCollectorSpecificationMock()
    {
        return $this->getMock(CollectorSpecificationInterface::class);
    }
}
