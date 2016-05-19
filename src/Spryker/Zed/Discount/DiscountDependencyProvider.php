<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Fixed;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Percentage;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemBySkuCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToAssertionBridge;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridge;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Plugin\Connection;

class DiscountDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE_CONFIG = 'STORE_CONFIG';
    const FACADE_MESSENGER = 'MESSENGER_FACADE';
    const FACADE_TAX = 'TAX_FACADE';
    const FACADE_ASSERTION = 'FACADE_ASSERTION';

    const PLUGIN_PROPEL_CONNECTION = 'PROPEL_CONNECTION_PLUGIN';

    const PLUGIN_DECISION_RULE_VOUCHER = 'PLUGIN_DECISION_RULE_VOUCHER';
    const PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL = 'PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL';

    const PLUGIN_COLLECTOR_ITEM = 'PLUGIN_COLLECTOR_ITEM';
    const PLUGIN_COLLECTOR_ITEM_PRODUCT_OPTION = 'PLUGIN_COLLECTOR_ITEM_PRODUCT_OPTION';
    const PLUGIN_COLLECTOR_AGGREGATE = 'PLUGIN_COLLECTOR_AGGREGATE';
    const PLUGIN_COLLECTOR_ORDER_EXPENSE = 'PLUGIN_COLLECTOR_ORDER_EXPENSE';
    const PLUGIN_COLLECTOR_ITEM_EXPENSE = 'PLUGIN_COLLECTOR_ITEM_EXPENSE';

    const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';
    const PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';

    const DECISION_RULE_PLUGINS = 'DECISION_RULE_PLUGINS';
    const CALCULATOR_PLUGINS = 'CALCULATOR_PLUGINS';
    const COLLECTOR_PLUGINS = 'COLLECTOR_PLUGINS';


    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function () {
            return Store::getInstance();
        };

        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new DiscountToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function () {
            return (new Connection())->get();
        };

        $container[self::CALCULATOR_PLUGINS] = function () {
            return $this->getAvailableCalculatorPlugins();
        };

        $container[self::DECISION_RULE_PLUGINS] = function () {
            return $this->getDecisionRulePlugins();
        };

        $container[self::CALCULATOR_PLUGINS] = function () {
            return $this->getAvailableCalculatorPlugins();
        };

        $container[self::COLLECTOR_PLUGINS] = function () {
            return $this->getCollectorPlugins();
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new DiscountToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_ASSERTION] = function (Container $container) {
            return new DiscountToAssertionBridge($container->getLocator()->assertion()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function () {
            return Store::getInstance();
        };

        $container[self::DECISION_RULE_PLUGINS] = function () {
            return $this->getDecisionRulePlugins();
        };

        $container[self::CALCULATOR_PLUGINS] = function () {
            return $this->getAvailableCalculatorPlugins();
        };

        $container[self::COLLECTOR_PLUGINS] = function () {
            return $this->getCollectorPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getAvailableCalculatorPlugins()
    {
        return [
            self::PLUGIN_CALCULATOR_PERCENTAGE => new Percentage(),
            self::PLUGIN_CALCULATOR_FIXED => new Fixed(),
        ];
    }

    /**
     * @return CollectorPluginInterface[]
     */
    protected function getCollectorPlugins()
    {
        return [
            new ItemBySkuCollectorPlugin()
        ];
    }

    /**
     * @return DecisionRulePluginInterface[]
     */
    protected function getDecisionRulePlugins()
    {
        return [
            new SkuDecisionRulePlugin(),
        ];
    }

}
