<?php

namespace Alekseon\CustomFormsBuilder\Test\Integration\Model;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Alekseon\CustomFormsBuilder\Model\FormRepository;

class FormRepositoryTest extends TestCase
{

    private $formRepository;

    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();

        $this->formRepository = $objectManager->create(FormRepository::class);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testInvalidGetById()
    {
        $this->formRepository->getById(99);
    }

}