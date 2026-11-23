<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Test\Unit\Model\FormRecord;

use Alekseon\CustomFormsBuilder\Model\FormRecord\Attribute;
use Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class AttributeRepositoryTest
 * @package Alekseon\CustomFormsBuilder\Test\Unit\Model\FormRecord
 */
class AttributeRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockObject|AttributeRepository
     */
    private $repository;

    /**
     * Initialize repository
     */
    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(AttributeRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
    }

    /**
     * @param int $formId
     * @return MockObject|Attribute
     */
    private function mockAttribute(int $formId)
    {
        $attribute = $this->getMockBuilder(Attribute::class)
            ->disableOriginalConstructor()
            ->addMethods(['getFormId'])
            ->getMock();
        $attribute->method('getFormId')->willReturn((string) $formId);
        return $attribute;
    }

    public function testGetByIdForFormReturnsFieldOfGivenForm()
    {
        $attribute = $this->mockAttribute(5);
        $this->repository->method('getById')->with(17)->willReturn($attribute);

        $this->assertSame($attribute, $this->repository->getByIdForForm(17, 5));
    }

    public function testGetByIdForFormRejectsFieldOfOtherForm()
    {
        $this->repository->method('getById')->with(17)->willReturn($this->mockAttribute(7));

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getByIdForForm(17, 5);
    }

    public function testGetByIdForFormRejectsEmptyFormId()
    {
        $this->repository->method('getById')->with(17)->willReturn($this->mockAttribute(0));

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getByIdForForm(17, 0);
    }
}
