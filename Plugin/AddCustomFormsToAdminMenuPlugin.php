<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Plugin;

/**
 * Class AddWidgetFormsToAdminMenuPlugin
 * @package Alekseon\CustomFormsBuilder\Plugin
 */
class AddCustomFormsToAdminMenuPlugin
{
    /**
     * @var \Magento\Backend\Model\Menu\Item\Factory
     */
    protected $menuItemFactory;
    /**
     * @var \Alekseon\WidgetForms\Model\ResourceModel\Form\CollectionFactory
     */
    protected $formCollectionFactory;

    /**
     * AddWidgetFormsToAdminMenuPlugin constructor.
     * @param \Magento\Backend\Model\Menu\Item\Factory $menuItemFactory
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
     */
    public function __construct(
        \Magento\Backend\Model\Menu\Item\Factory $menuItemFactory,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
    )
    {
        $this->menuItemFactory = $menuItemFactory;
        $this->formCollectionFactory = $formCollectionFactory;
    }

    /**
     * @param $builder
     * @param $menu
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetResult($builder, $menu)
    {
        $formCollection = $this->formCollectionFactory->create();
        $formCollection->addAttributeToSelect('title');
        $formCollection->addAttributeToFilter('show_in_menu', true);

        foreach ($formCollection as $form) {
            $defaultTitle = __('Form #%1', $form->getId());

            $title = $form->getTitle();
            if (strlen($title) < 3) {
                $title = $defaultTitle;
            }
            if (strlen($title) > 50) {
                $title = substr($title, 0, 50);
            }

            $params = [
                'id' => 'custom_form_' . $form->getId(),
                'title' => $title,
                'resource' => 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId(),
                'action' => 'alekseon_customFormsBuilder/formRecord/index/id/' . $form->getId(),
            ];

            try {
                $item = $this->menuItemFactory->create($params);
            } catch (\InvalidArgumentException $e) {
                $params['title'] = (string) $defaultTitle;
                $item = false;
            }

            try {
                if (!$item) {
                    $item = $this->menuItemFactory->create($params);
                }
                $contentElements = $menu->get('Alekseon_CustomFormsBuilder::custom_forms');
                $contentElements->getChildren()->add(
                    $item,
                    null,
                    1
                );
            } catch (\Exception $e) {

            }
        }

        return $menu;
    }
}
