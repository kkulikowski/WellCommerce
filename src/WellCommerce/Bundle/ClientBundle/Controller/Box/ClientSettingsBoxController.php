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

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;

/**
 * Class ClientSettingsBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 *
 * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Template()
 */
class ClientSettingsBoxController extends AbstractBoxController
{
    public function indexAction(Request $request)
    {
        $manager = $this->getManager();
        $client  = $manager->getRequestHelper()->getClient();
        if (null === $client) {
            return $manager->getRedirectHelper()->redirectTo('front.client.login');
        }

        $form = $this->get('client_contact_details.form_builder.front')->createForm([
            'name' => 'settings'
        ], $client);

        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $manager->updateResource($client, $request);

                $manager->getFlashHelper()->addSuccess('client.flash.contact_details.success');

                return $manager->getRedirectHelper()->redirectTo('front.client.settings');
            }

            if (count($form->getError())) {
                $manager->getFlashHelper()->addError('client.flash.contact_details.error');
            }
        }

        return [
            'form'     => $form,
            'elements' => $form->getChildren(),
        ];
    }
}
