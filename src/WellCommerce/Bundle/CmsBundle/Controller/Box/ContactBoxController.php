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

namespace WellCommerce\Bundle\CmsBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\CoreBundle\Controller\Box\BoxControllerInterface;

/**
 * Class ContactBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 *
 * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Template()
 */
class ContactBoxController extends AbstractBoxController implements BoxControllerInterface
{
    public function indexAction(Request $request)
    {
        return [];
    }
}
