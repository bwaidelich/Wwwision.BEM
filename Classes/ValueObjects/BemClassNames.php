<?php
declare(strict_types=1);
namespace Wwwision\BEM\ValueObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

/**
 * Value Object representing BEM Style class attributes (see http://getbem.com)
 * It implements ProtectedContextAwareInterface so that it can be used from within Eel expressions
 * @see \Wwwision\BEM\Fusion\BemClassesImplementation
 * @see \Wwwision\BEM\EelHelper\BemHelper
 *
 * @Flow\Proxy(false)
 */
final class BemClassNames implements ProtectedContextAwareInterface
{
    private const MODIFIER_SEPARATOR = '--';
    private const ELEMENT_SEPARATOR = '__';

    /**
     * @var string
     */
    private $block;

    /**
     * @var array
     */
    private $modifiers;

    /**
     * @param string $block The Base class name (aka Block)
     * @param array $modifiers List of modifiers. Each modifier without the block part and without the separator ("--")
     */
    public function __construct(string $block, array $modifiers)
    {
        if ($block === '') {
            throw new \InvalidArgumentException('The block must not be empty', 1554368542);
        }
        foreach ($modifiers as $modifier) {
            if (strpos($modifier, self::MODIFIER_SEPARATOR) !== false) {
                throw new \InvalidArgumentException(sprintf('Modifiers must not contain modifier separators (%s) - the block can be excluded from modifier "%s"', self::MODIFIER_SEPARATOR, $modifier), 1554368767);
            }
        }
        $this->block = $block;
        $this->modifiers = $modifiers;
    }

    /**
     * Extends the original block with the specified extension, separated by a hyphen
     *
     * $someBlock->extend('sub'); // => 'some-block-sub'
     *
     * @param string $extension name of the sub-block
     * @param array $modifiers optional list of modifiers (@see __construct())
     * @return self
     */
    public function extend(string $extension, array $modifiers = []): self
    {
        return new static($this->block . '-' . $extension, $modifiers);
    }

    /**
     * Returns the original base class (aka Block) as string
     *
     * @return string
     */
    public function getBlock(): string
    {
        return $this->block;
    }

    /**
     * Renders one or more BEM element class name(s) for the base class and the given element name(s)
     *
     * $someBlock->element('foo', 'bar'); // => 'some-block__foo some-block__bar'
     *
     * @param string ...$elementNames
     * @return string
     */
    public function element(string ...$elementNames): string
    {
        $classNames = array_map(function(string $elementName) {
            if ($elementName === '') {
                throw new \InvalidArgumentException('The element must not be empty', 1554369033);
            }
            if (strpos($elementName, self::ELEMENT_SEPARATOR) !== false) {
                throw new \InvalidArgumentException(sprintf('Elements must not contain element separators (%s) - the block can be excluded from element "%s"', self::ELEMENT_SEPARATOR, $elementName), 1554369008);
            }
            return $this->block . self::ELEMENT_SEPARATOR . $elementName;
        }, $elementNames);
        return implode(' ', $classNames);
    }

    /**
     * Render the class name(s) as string
     * For example: "some-block some-block--modifier-1 some-block--modifier-2"
     *
     * @return string
     */
    public function render(): string
    {
        $classNames = array_map(function(string $modifier) {
            return $this->block . self::MODIFIER_SEPARATOR . $modifier;
        }, $this->modifiers);
        array_unshift($classNames, $this->block);
        return implode(' ', $classNames);
    }

    /**
     * Allow all methods to be invoked from within Eel expressions
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }

    /**
     * Render the class name(s)
     * @see render()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
