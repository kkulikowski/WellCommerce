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
namespace WellCommerce\Bundle\CmsBundle\Form\Admin;

use WellCommerce\Bundle\FormBundle\Builder\AbstractFormBuilder;
use WellCommerce\Bundle\FormBundle\Builder\FormBuilderInterface;
use WellCommerce\Bundle\FormBundle\Conditions\Equals;
use WellCommerce\Bundle\FormBundle\DataTransformer\CollectionToArrayTransformer;
use WellCommerce\Bundle\FormBundle\DataTransformer\EntityToIdentifierTransformer;
use WellCommerce\Bundle\FormBundle\DataTransformer\TranslationTransformer;
use WellCommerce\Bundle\FormBundle\Elements\FormInterface;

/**
 * Class PageFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class PageFormBuilder extends AbstractFormBuilder implements FormBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormInterface $form)
    {
        $this->addMainFieldset($form);
        $this->addContentFieldset($form);
        $this->addRedirectFieldset($form);
        $this->addShopFieldset($form);

        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }

    /**
     * Adds main settings fieldset to form
     *
     * @param FormInterface $form
     */
    private function addMainFieldset(FormInterface $form)
    {
        $mainData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => $this->trans('fieldset.main.label')
        ]));

        $languageData = $mainData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('fieldset.translations.label'),
            'transformer' => new TranslationTransformer($this->get('page.repository'))
        ]));

        $name = $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => $this->trans('page.name.label'),
        ]));

        $languageData->addChild($this->getElement('slug_field', [
            'name'            => 'slug',
            'label'           => $this->trans('page.slug.label'),
            'name_field'      => $name,
            'generate_route'  => 'admin.routing.generate',
            'translatable_id' => $this->getParam('id')
        ]));

        $mainData->addChild($this->getElement('checkbox', [
            'name'    => 'publish',
            'label'   => $this->trans('page.publish.label'),
            'comment' => $this->trans('page.publish.help'),
            'default' => 1
        ]));

        $mainData->addChild($this->getElement('text_field', [
            'name'    => 'hierarchy',
            'label'   => $this->trans('page.hierarchy.label'),
            'default' => 0
        ]));

        $mainData->addChild($this->getElement('tree', [
            'name'        => 'parent',
            'label'       => $this->trans('page.parent.label'),
            'choosable'   => true,
            'selectable'  => false,
            'sortable'    => false,
            'clickable'   => false,
            'items'       => $this->get('page.collection.admin')->getFlatTree(),
            'restrict'    => $this->getParam('id'),
            'transformer' => new EntityToIdentifierTransformer($this->get('page.repository'))
        ]));

        $mainData->addChild($this->getElement('tip', [
            'tip' => $this->trans('page.tip.client_groups')
        ]));

        $mainData->addChild($this->getElement('multi_select', [
            'name'        => 'clientGroups',
            'label'       => $this->trans('page.label.client_groups'),
            'options'     => $this->get('client_group.collection')->getSelect(),
            'transformer' => new CollectionToArrayTransformer($this->get('client_group.repository'))
        ]));
    }

    /**
     * Adds content editing fieldset to form
     *
     * @param FormInterface $form
     */
    private function addContentFieldset(FormInterface $form)
    {
        $contentData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'content_data',
            'label' => $this->trans('fieldset.content.label')
        ]));

        $languageData = $contentData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('fieldset.translations.label'),
            'transformer' => new TranslationTransformer($this->get('page.repository'))
        ]));

        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'content',
            'label' => $this->trans('page.content.label'),
        ]));

        $metaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'meta_data',
            'label' => $this->trans('fieldset.meta.label')
        ]));

        $languageData = $metaData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('fieldset.translations.label'),
            'transformer' => new TranslationTransformer($this->get('producer.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'meta.title',
            'label' => $this->trans('meta.title.label')
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'meta.keywords',
            'label' => $this->trans('meta.keywords.label'),
        ]));

        $languageData->addChild($this->getElement('text_area', [
            'name'  => 'meta.description',
            'label' => $this->trans('meta.description.label'),
        ]));
    }

    private function addRedirectFieldset(FormInterface $form)
    {
        $redirectSettings = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'redirect_settings',
            'label' => $this->trans('label.redirect_settings')
        ]));

        $redirectType = $redirectSettings->addChild($this->getElement('select', [
            'name'    => 'redirectType',
            'label'   => $this->trans('label.redirect_type'),
            'options' => [
                0 => $this->trans('label.redirect_type.none'),
                1 => $this->trans('label.redirect_type.url'),
                2 => $this->trans('label.redirect_type.route'),
            ]
        ]));

        $redirectSettings->addChild($this->getElement('text_field', [
            'name'         => 'redirectUrl',
            'label'        => $this->trans('label.redirect_url'),
            'dependencies' => [
                $this->getDependency('show', [
                    'form'      => $form,
                    'field'     => $redirectType,
                    'condition' => new Equals(1)
                ])
            ]
        ]));

        $redirectSettings->addChild($this->getElement('select', [
            'name'         => 'redirectRoute',
            'label'        => $this->trans('label.redirect_route'),
            'options'      => $this->getRedirectRoutes(),
            'dependencies' => [
                $this->getDependency('show', [
                    'form'      => $form,
                    'field'     => $redirectType,
                    'condition' => new Equals(2)
                ])
            ]
        ]));
    }

    /**
     * Adds shop selector fieldset to form
     *
     * @param FormInterface $form
     */
    private function addShopFieldset(FormInterface $form)
    {
        $shopsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'shops_data',
            'label' => $this->trans('fieldset.shops.label')
        ]));

        $shopsData->addChild($this->getElement('multi_select', [
            'name'        => 'shops',
            'label'       => $this->trans('shops.label'),
            'options'     => $this->get('shop.collection')->getSelect(),
            'transformer' => new CollectionToArrayTransformer($this->get('shop.repository'))
        ]));
    }

    /**
     * Returns all available routes which have allow_page_redirect option
     *
     * @return array
     */
    private function getRedirectRoutes()
    {
        $availableRoutes = [];

        /**
         * @var $route \Symfony\Component\Routing\Route
         */
        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
            if ($route->hasOption('allow_page_redirect')) {
                $availableRoutes[$name] = $route->getPath();
            }
        }

        return $availableRoutes;
    }
}
