<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class FormField
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml
 */
abstract class FormField extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * FormField constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository $attributeRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository $attributeRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->attributeRepository = $attributeRepository;
        parent::__construct($context);
    }

    /**
     * @param string $requestParam
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initAttribute($requestParam = 'id')
    {
        $attribute = $this->coreRegistry->registry('current_attribute');
        if (!$attribute) {
            $attributeId = $this->getRequest()->getParam($requestParam, false);
            if ($attributeId) {
                $attribute = $this->attributeRepository->getById($attributeId);
            } else {
                return false;
            }
            $this->coreRegistry->register('current_attribute', $attribute);
        }
        return $attribute;
    }

    /**
     * @param string $path
     * @param array $params
     * @return mixed
     */
    protected function returnResult($path = '', array $params = [])
    {
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }
}
