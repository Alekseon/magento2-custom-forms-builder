<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class FormRepository
 * @package Alekseon\CustomFormsBuilder\Model
 */
class FormRepository
{
    /**
     * @var \Alekseon\CustomFormBuilder\Model\FormFactory
     */
    protected $formFactory;
    /**
     * @var array
     */
    protected $loadedForms = [];

    /**
     * FormRepository constructor.
     * @param FormFactory $formFactory
     */
    public function __construct(
        \Alekseon\CustomFormsBuilder\Model\FormFactory $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    /**
     * @param $formId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($formId, $storeId = null, $graceful = false)
    {
        if (!isset($this->loadedForms[$formId])) {
            $form = $this->formFactory->create();
            $form->setStoreId($storeId);
            $form->getResource()->load($form, $formId);
            if (!$form->getId()) {
                if ($graceful) {
                    return $form;
                } else {
                    throw new NoSuchEntityException(__('Form with id "%1" does not exist.', $formId));
                }
            }
            $this->loadedForms[$formId] = $form;
        }
        return $this->loadedForms[$formId];
    }
}
