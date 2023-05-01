<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Plugin;

use Magento\Framework\Acl\AclResource\TreeBuilder;
use Magento\User\Block\Role\Tab\Edit;

/**
 * Class AddCustomFormsToAclTreePlugin
 * @package Alekseon\CustomFormsBuilder\Plugin
 */
class AddCustomFormsToAclTreePlugin
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
     * AddCustomFormsToAdminMenuPlugin constructor.
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
     */
    public function __construct(
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
    )
    {
        $this->formCollectionFactory = $formCollectionFactory;
    }

    /**
     * @param Edit $roleTabEdit
     * @param $tree
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterBuild(TreeBuilder $treeBuilder, $result)
    {

        foreach ($result as $key => $resultElement) {
            if ($resultElement['id'] == 'Alekseon_CustomFormsBuilder::custom_forms') {

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

                    $result[$key]['children'][] = [
                        'id' => 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId(),
                        'title' => $title,
                        'sortOrder' => 1,
                        'children' => [
                            [
                                'id' => 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId() . '_save',
                                'title' => __('Save'),
                                'sortOrder' => 1,
                                'children' => [],
                            ]
                        ],
                    ];
                }
            }
        }

        return $result;
    }
}
