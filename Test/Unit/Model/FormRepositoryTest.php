<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Test\Unit\Model;

use Alekseon\CustomFormsBuilder\Model\FormRepository;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class FormRepositoryTest
 * @package Alekseon\CustomFormsBuilder\Test\Unit\Model
 */
class FormRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockObject|\Alekseon\CustomFormsBuilder\Model\Form
     */
    protected $form;

    /**
     * @var MockObject|\Alekseon\CustomFormsBuilder\Model\ResourceModel\Form
     */
    protected $formResource;

    /**
     * @var MockObject|\Alekseon\CustomFormsBuilder\Model\FormRepository
     */
    protected $formRepository;

    /**
     * Initialize repository
     */
    protected function setUp(): void
    {

        $formFactory = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\FormFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->formResource = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\ResourceModel\Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->form = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\Form::class)->disableOriginalConstructor()->getMock();

        $formFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->form);

        $this->formRepository = new FormRepository(
            $formFactory
        );
    }

    /**
     * @test
     */
    public function testGetByIdException()
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $formId = '123';

        $this->form->expects($this->once())
            ->method('getId')
            ->willReturn(false);
        $this->formResource->expects($this->once())
            ->method('load')
            ->with($this->form, $formId)
            ->willReturn($this->form);
        $this->form->expects($this->once())
            ->method('getResource')
            ->willReturn($this->formResource);
        $this->formRepository->getById($formId);
    }

}
