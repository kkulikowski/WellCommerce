<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace WellCommerce\Bundle\PaymentBundle\DataGrid;

use WellCommerce\Bundle\DataGridBundle\AbstractDataGrid;
use WellCommerce\Bundle\DataGridBundle\Column\Column;
use WellCommerce\Bundle\DataGridBundle\Column\ColumnCollection;
use WellCommerce\Bundle\DataGridBundle\Column\Options\Appearance;
use WellCommerce\Bundle\DataGridBundle\Column\Options\Filter;
use WellCommerce\Bundle\DataGridBundle\Column\Options\Sorting;
use WellCommerce\Bundle\DataGridBundle\DataGridInterface;

/**
 * Class PaymentMethodDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PaymentMethodDataGrid extends AbstractDataGrid implements DataGridInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureColumns(ColumnCollection $collection)
    {
        $collection->add(new Column([
            'id'         => 'id',
            'caption'    => $this->trans('payment.id.label'),
            'sorting'    => new Sorting([
                'default_order' => Sorting::SORT_DIR_DESC,
            ]),
            'appearance' => new Appearance([
                'width'   => 90,
                'visible' => false,
            ]),
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));

        $collection->add(new Column([
            'id'      => 'name',
            'caption' => $this->trans('payment.name.label')
        ]));

        $collection->add(new Column([
            'id'      => 'processor',
            'caption' => $this->trans('payment.processor.label')
        ]));

    }
}
