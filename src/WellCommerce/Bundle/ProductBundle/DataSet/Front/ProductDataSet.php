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

namespace WellCommerce\Bundle\ProductBundle\DataSet\Front;

use WellCommerce\Bundle\DataSetBundle\AbstractDataSet;
use WellCommerce\Bundle\DataSetBundle\DataSetConfiguratorInterface;
use WellCommerce\Bundle\DataSetBundle\DataSetInterface;
use WellCommerce\Bundle\ProductBundle\DataSet\Transformer\ProductStatusTransformer;

/**
 * Class ProductDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductDataSet extends AbstractDataSet implements DataSetInterface
{
    /**
     * {@inheritdoc}
     */
    /**
     * {@inheritdoc}
     */
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'               => 'product.id',
            'name'             => 'product_translation.name',
            'shortDescription' => 'product_translation.shortDescription',
            'description'      => 'product_translation.description',
            'route'            => 'IDENTITY(product_translation.route)',
            'weight'           => 'product.weight',
            'price'            => 'product.sellPrice.amount',
            'discountedPrice'  => 'product.sellPrice.discountedAmount',
            'isDiscountValid'  => 'IF_ELSE(:date BETWEEN product.sellPrice.validFrom AND product.sellPrice.validTo, 1, 0)',
            'currency'         => 'product.sellPrice.currency',
            'tax'              => 'sell_tax.value',
            'stock'            => 'product.stock',
            'producerName'     => 'producers_translation.name',
            'category'         => 'categories.id',
            'shop'             => 'product_shops.id',
            'photo'            => 'photos.path',
            'status'           => 'statuses.id',
        ]);

        $statuses = $this->container->get('product_status.collection.front')->getArray();

        $configurator->setTransformers([
            'status' => new ProductStatusTransformer($statuses)
        ]);
    }
}
