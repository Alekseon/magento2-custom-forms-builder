<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Setup\Patch\Data;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Alekseon\AlekseonEav\Setup\EavDataSetup;
use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;
use Alekseon\AlekseonEav\Setup\EavDataSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 *
 */
class CreateWidgetFormsAttributesPatch implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavDataSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var AttributeRepository
     */
    private $formAttributeRepository;

    /**
     * @param EavDataSetupFactory $eavSetupFactory
     * @param AttributeRepository $formAttributeRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavDataSetupFactory $eavSetupFactory,
        AttributeRepository $formAttributeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->formAttributeRepository = $formAttributeRepository;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavDataSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $this->createFormAttributes($eavSetup);

        $this->moduleDataSetup->getConnection()->endSetup();
        return $this;
    }

    /**
     * @param $eavSetup
     * @return void
     */
    private function createFormAttributes($eavSetup)
    {
        $eavSetup->createAttribute(
            'title',
            [
                'frontend_input' => 'text',
                'frontend_label' => 'Title',
                'visible_in_grid' => true,
                'is_required' => true,
                'sort_order' => 10,
                'scope' => Scopes::SCOPE_STORE,
            ]
        );

        $eavSetup->createAttribute(
            'show_in_menu',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Show in adminhtml menu',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 40,
                'scope' => Scopes::SCOPE_GLOBAL,
                'note' => __('Menu -> Marketing -> Custom Forms'),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->deleteAttribute('title');
        $eavSetup->deleteAttribute('show_in_menu');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
