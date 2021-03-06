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

namespace WellCommerce\Bundle\IntlBundle\Controller\Front;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\CoreBundle\Controller\Front\FrontControllerInterface;

/**
 * Class LocaleController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LocaleController extends AbstractFrontController implements FrontControllerInterface
{
    /**
     * Redirects to home page
     *
     * @return RedirectResponse
     */
    public function switchAction(Request $request)
    {
        return new RedirectResponse($request->headers->get('referer'));
    }
}
