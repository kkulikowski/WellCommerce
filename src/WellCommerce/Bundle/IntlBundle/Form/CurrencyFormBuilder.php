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
namespace WellCommerce\Bundle\IntlBundle\Form;

use WellCommerce\Bundle\FormBundle\Builder\AbstractFormBuilder;
use WellCommerce\Bundle\FormBundle\Builder\FormBuilderInterface;
use WellCommerce\Bundle\FormBundle\Elements\FormInterface;
use WellCommerce\Bundle\IntlBundle\Repository\CurrencyRepositoryInterface;

/**
 * Class CurrencyFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyFormBuilder extends AbstractFormBuilder implements FormBuilderInterface
{
    /**
     * @var CurrencyRepositoryInterface
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => $this->trans('form.required_data.label')
        ]));

        $requiredData->addChild($this->getElement('select', [
            'name'    => 'code',
            'label'   => $this->trans('currency.code.label'),
            'options' => $this->repository->getCurrenciesToSelect()
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }

    /**
     * Sets currency repository
     *
     * @param CurrencyRepositoryInterface $repository
     */
    public function setRepository(CurrencyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
