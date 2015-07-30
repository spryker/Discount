<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class VoucherPoolTable extends AbstractTable
{
    const COL_OPTIONS = 'options';

    /**
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $poolQuery;

    /**
     * @param SpyDiscountVoucherPoolQuery $discountVoucherPool
     */
    public function __construct(SpyDiscountVoucherPoolQuery $discountVoucherPool)
    {
        $this->poolQuery = $discountVoucherPool;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl('poolTable');

        $config->setHeader([
            SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL => 'Id',
            SpyDiscountVoucherPoolTableMap::COL_NAME => 'Name',
            self::COL_OPTIONS => 'Options',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $results = [];

        $queryResults = $this->runQuery($this->poolQuery, $config);

        foreach ($queryResults as $item) {
            $results[] = [
                SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL => $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL],
                SpyDiscountVoucherPoolTableMap::COL_NAME => $item[SpyDiscountVoucherPoolTableMap::COL_NAME],
                self::COL_OPTIONS => sprintf(
                    '<a href="/discount/pool/edit?id=%d" class="btn btn-sm btn-primary">Edit</a>',
                    $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL]
                ),
            ];
        }

        return $results;
    }

}
