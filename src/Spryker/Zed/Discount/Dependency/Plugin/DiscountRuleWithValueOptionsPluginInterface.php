<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

interface DiscountRuleWithValueOptionsPluginInterface
{

    /**
     * List of key-value pairs of available select options.
     *
     * @api
     *
     * @return array
     */
    public function getQueryStringValueOptions();

}
