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

namespace WellCommerce\Bundle\CategoryBundle\Manager\Front;

use WellCommerce\Bundle\CategoryBundle\Entity\Category;
use WellCommerce\Bundle\CoreBundle\Manager\Front\AbstractFrontManager;
use WellCommerce\Bundle\DataSetBundle\Conditions\Condition\Eq;
use WellCommerce\Bundle\DataSetBundle\Conditions\ConditionsCollection;

/**
 * Class CategoryManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryManager extends AbstractFrontManager
{
    /**
     * Returns a collection of dynamic conditions
     *
     * @return ConditionsCollection
     */
    public function getConditions()
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('category', $this->getCurrentCategoryId()));

        return $conditions;
    }

    /**
     * Returns categories id from provider service
     *
     * @return int
     */
    public function getCurrentCategoryId()
    {
        $category = $this->getCategoryProvider()->getCurrentCategory();

        if ($category instanceof Category) {
            return $category->getId();
        }

        return null;
    }
}
