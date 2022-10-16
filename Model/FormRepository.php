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
    protected $loadedFormsByIds = [];
    /**
     * @var array
     */
    protected $loadedFormsByCodes = [];

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
        $storeKey = $storeId ?? 'null';
        if (!isset($this->loadedFormsByIds[$formId][$storeKey])) {
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
            $this->addFormToLoaded($form, $storeKey);
        }

        return $this->loadedFormsByIds[$formId][$storeKey];
    }

    /**
     * @param $formId
     * @param null $storeId
     * @param false $graceful
     */
    public function getByCode($formCode, $storeId = null, $graceful = true)
    {
        $storeKey = $storeId ?? 'null';
        if (!isset($this->loadedFormsByCodes[$formCode])) {
            $form = $this->formFactory->create();
            $form->setStoreId($storeId);
            $form->getResource()->load($form, $formCode, 'form_code');
            if (!$form->getId()) {
                if ($graceful) {
                    return $form;
                } else {
                    throw new NoSuchEntityException(__('Form with code "%1" does not exist.', $formCode));
                }
            }
            $this->addFormToLoaded($form, $storeKey);
        }

        $formId = $this->loadedFormsByCodes[$formCode];
        return $this->loadedFormsByIds[$formId][$storeKey];
    }

    /**
     * @param $form
     */
    protected function addFormToLoaded($form, $storeKey = 'null')
    {
        $this->loadedFormsByIds[$form->getId()][$storeKey] = $form;
        if ($form->getFormCode()) {
            $this->loadedFormsByCodes[$form->getFormCode()] = $form->getId();
        }
    }
}
