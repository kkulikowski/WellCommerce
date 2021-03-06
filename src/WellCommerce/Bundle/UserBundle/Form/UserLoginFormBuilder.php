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
namespace WellCommerce\Bundle\UserBundle\Form;

use WellCommerce\Bundle\FormBundle\Builder\AbstractFormBuilder;
use WellCommerce\Bundle\FormBundle\Builder\FormBuilderInterface;
use WellCommerce\Bundle\FormBundle\Elements\FormInterface;

/**
 * Class UserLoginForm
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UserLoginFormBuilder extends AbstractFormBuilder implements FormBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('tip', [
            'tip'  =>
                '<p>Default credentials:<br/><br/>
                    <strong>Username:</strong> admin<br/>
                    <strong>Login:</strong> admin<br/>
                </p>',
        ]));

        $form->addChild($this->getElement('text_field', [
            'name'  => '_username',
            'label' => $this->trans('user.label.username'),
        ]));

        $form->addChild($this->getElement('password', [
            'name'  => '_password',
            'label' => $this->trans('user.label.password'),
        ]));

        $form->addChild($this->getElement('submit', [
            'name'  => 'log_in',
            'label' => $this->trans('user.button.log_in'),
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
