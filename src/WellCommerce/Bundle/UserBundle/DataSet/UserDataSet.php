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

namespace WellCommerce\Bundle\UserBundle\DataSet;

use WellCommerce\Bundle\DataSetBundle\AbstractDataSet;
use WellCommerce\Bundle\DataSetBundle\DataSetConfiguratorInterface;
use WellCommerce\Bundle\DataSetBundle\DataSetInterface;

/**
 * Class UserDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UserDataSet extends AbstractDataSet implements DataSetInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'         => 'user.id',
            'username'   => 'user.username',
            'name'       => 'CONCAT(user.firstName,\' \', user.lastName)',
            'first_name' => 'user.firstName',
            'last_name'  => 'user.lastName',
            'email'      => 'user.email',
            'enabled'    => 'user.enabled',
        ]);
    }
}
