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

namespace WellCommerce\Bundle\ShippingBundle\DataSet\Admin;

use WellCommerce\Bundle\DataSetBundle\AbstractDataSet;
use WellCommerce\Bundle\DataSetBundle\DataSetConfiguratorInterface;
use WellCommerce\Bundle\DataSetBundle\DataSetInterface;

/**
 * Class ShippingMethodDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethodDataSet extends AbstractDataSet implements DataSetInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'         => 'shipping_method.id',
            'name'       => 'shipping_method_translation.name',
            'calculator' => 'shipping_method.calculator',
            'hierarchy'  => 'shipping_method.hierarchy',
        ]);
    }
}
