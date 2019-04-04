<?php
declare(strict_types=1);
namespace Wwwision\BEM\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Wwwision\BEM\ValueObjects\BemClassNames;

/**
 * BEM related Eel Helpers (see http://getbem.com)
 *
 * @Flow\Scope("singleton")
 */
final class BemHelper implements ProtectedContextAwareInterface
{

    /**
     * Returns a BEM block that can be rendered or modified:
     *
     * ${BEM.block('some-component')} // => "some-component"
     * ${BEM.block('some-component', ['foo', 'bar'])} // => "some-component some-component--foo some-component--bar"
     *
     * ${BEM.block('some-component').element('some-element')} // => "some-component__some-element"
     *
     * @param string $block
     * @param array $modifiers
     * @return BemClassNames
     */
    public function block(string $block, array $modifiers = []): BemClassNames
    {
        return new BemClassNames($block, $modifiers);
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
