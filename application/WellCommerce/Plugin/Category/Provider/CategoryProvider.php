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

namespace WellCommerce\Plugin\Category\Provider;

use WellCommerce\Core\Component\AbstractComponent;
use WellCommerce\Plugin\Category\Model\Category;

/**
 * Class CategoryProvider
 *
 * @package WellCommerce\Plugin\Category\Provider
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryProvider extends AbstractComponent
{
    /**
     * Current category data
     *
     * @var
     */
    private $data;

    /**
     * Sets model for currently viewed category
     *
     * @param Category $product
     */
    public function setCurrent(Category $category)
    {
        $this->data = $category;
    }

    /**
     * Returns normalized product data
     *
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->data;
    }
} 