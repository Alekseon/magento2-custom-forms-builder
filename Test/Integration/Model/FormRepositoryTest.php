<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Test\Integration\Model;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Alekseon\CustomFormsBuilder\Model\FormRepository;

/**
 * Class FormRepositoryTest
 * @package Alekseon\CustomFormsBuilder\Test\Integration\Model
 */
class FormRepositoryTest extends TestCase
{
    private $formRepository;

    protected function setUp(): void
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
