<?php
declare(strict_types=1);
namespace Wwwision\BEM\Tests\Unit\ValueObjects;

use Neos\Flow\Tests\UnitTestCase;
use PHPUnit\Framework\Assert;
use Wwwision\BEM\ValueObjects\BemClassNames;

class BemClassNamesTest extends UnitTestCase
{

    /**
     * @test
     */
    public function constructorDoesNotAcceptEmptyBlocks(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new BemClassNames('', []);
    }

    /**
     * @test
     */
    public function constructorDoesNotAcceptModifiersToContainModifierSeparators(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new BemClassNames('some-block', ['some-block--invalid']);
    }

    /**
     * @test
     */
    public function extendReturnsANewBlockWithoutTheOriginalModifiers(): void
    {
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        $newClassNames = $classNames->extend('extended');
        Assert::assertSame('some-block-extended', (string)$newClassNames);
    }

    /**
     * @test
     */
    public function extendReturnsANewBlockWithNewModifiersIfSpecified(): void
    {
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        $newClassNames = $classNames->extend('extended', ['m1']);
        Assert::assertSame('some-block-extended some-block-extended--m1', (string)$newClassNames);
    }

    /**
     * @test
     */
    public function getBlockReturnsOnlyTheBlockName(): void
    {
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        Assert::assertSame('some-block', $classNames->getBlock());
    }

    /**
     * @test
     */
    public function elementDoesNotAcceptEmptyElements(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        $classNames->element('');
    }

    /**
     * @test
     */
    public function elementDoesNotAcceptElementsWithElementSeparators(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $classNames = new BemClassNames('some-block', []);
        $classNames->element('first', 'some-block__invalid');
    }

    /**
     * @test
     */
    public function elementReturnsTheElementNameForAGivenBlock(): void
    {
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        Assert::assertSame('some-block__some-element', $classNames->element('some-element'));
    }

    /**
     * @test
     */
    public function elementReturnsMultipleElementsIfSpecified(): void
    {
        $classNames = new BemClassNames('some-block', ['modifier-1', 'modifier-2']);
        Assert::assertSame('some-block__some-element some-block__some-other-element', $classNames->element('some-element', 'some-other-element'));
    }

    public function renderDataProvider(): array
    {
        return [
            ['block' => 'some-block', 'modifiers' => [], 'expectedResult' => 'some-block'],
            ['block' => 'some-component', 'modifiers' => ['modifier-1'], 'expectedResult' => 'some-component some-component--modifier-1'],
            ['block' => 'some-component', 'modifiers' => ['modifier-1', 'modifier-2'], 'expectedResult' => 'some-component some-component--modifier-1 some-component--modifier-2'],
        ];
    }

    /**
     * @param string $block
     * @param array $modifiers
     * @param string $expectedResult
     * @test
     * @dataProvider renderDataProvider
     */
    public function renderTests(string $block, array $modifiers, string $expectedResult): void
    {
        $classNames = new BemClassNames($block, $modifiers);
        Assert::assertSame($expectedResult, $classNames->render());
    }

    /**
     * @test
     */
    public function toStringRendersTheClassNames(): void
    {
        $classNames = new BemClassNames('some-block', ['m1', 'm2']);
        Assert::assertSame('some-block some-block--m1 some-block--m2', (string)$classNames);
    }
}
