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

namespace WellCommerce\Bundle\ClientBundle\Controller\Box;

use Doctrine\Common\Util\Debug;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\CoreBundle\Controller\Box\BoxControllerInterface;

/**
 * Class ClientLoginBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 *
 * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Template()
 */
class ClientLoginBoxController extends AbstractBoxController
{
    public function indexAction()
    {
        $form = $this->get('client_login.form_builder.front')->createForm([
            'name'         => 'login',
            'ajax_enabled' => false,
            'action'       => $this->generateUrl('front.client.login_check')
        ], null);

        return [
            'form'     => $form,
            'elements' => $form->getChildren(),
            'error'    => $this->get('security.authentication_utils')->getLastAuthenticationError()
        ];
    }
}
