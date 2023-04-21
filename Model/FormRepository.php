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
    protected $loadedFormsByIdentifiers = [];

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
     * @return Form
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
     * @param $identifier
     * @param null $storeId
     * @param bool $graceful
     * @return Form
     * @throws NoSuchEntityException
     */
    public function getByIdentifier($identifier, $storeId = null, $graceful = true)
    {
        $storeKey = $storeId ?? 'null';
        if (!isset($this->loadedFormsByIdentifiers[$identifier])) {
            $form = $this->formFactory->create();
            $form->setStoreId($storeId);
            $form->getResource()->load($form, $identifier, 'identifier');
            if (!$form->getId()) {
                if ($graceful) {
                    return $form;
                } else {
                    throw new NoSuchEntityException(__('Form with identifier "%1" does not exist.', $identifier));
                }
            }
            $this->addFormToLoaded($form, $storeKey);
        }

        $formId = $this->loadedFormsByIdentifiers[$identifier];
        return $this->loadedFormsByIds[$formId][$storeKey];
    }

    /**
     * @param $form
     */
    protected function addFormToLoaded($form, $storeKey = 'null')
    {
        $this->loadedFormsByIds[$form->getId()][$storeKey] = $form;
        if ($form->getIdentifier()) {
            $this->loadedFormsByIdentifiers[$form->getIdentifier()] = $form->getId();
        }
    }
}
