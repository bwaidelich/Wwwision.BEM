<?php
declare(strict_types=1);
namespace Wwwision\BEM\Fusion;

use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Wwwision\BEM\ValueObjects\BemClassNames;

/**
 * Fusion implementation for BEM style class attributes (see http://getbem.com).
 *
 * Usage:
 *
 * class = Wwwision.BEM:Block {
 *   block = 'some-block'
 *   modifiers {
 *     'modifier-1' = ${truthyEelExpression}
 *     'modifier-2' = ${falsyEelExpression}
 *     dynamic = Wwwision.BEM:Modifier {
 *       name = ${'modifier-' + 3}
 *       active = ${trueByDefault}
 *     }
 * }
 *
 * => 'some-block some-block--modifier-1 some-block--modifier-3'
 *
 *
 * This prototype will return a value object (@see BemClassNames) that renders all classnames by default, but it can also be used to modify the result:
 *
 * class.element('some-element') // => 'some-block__some-element'
 * class.extend('sub-block') // => 'some-block-sub-block'
 *
 */
final class BlockImplementation extends AbstractFusionObject
{

    public function evaluate(): BemClassNames
    {
        $modifiers = $this->fusionValue('modifiers');
        $activeModifiers = [];
        foreach ($modifiers as $key => $value) {
            if (is_array($value)) {
                if (array_key_exists('active', $value) && $value['active'] !== true) {
                    continue;
                }
                $activeModifiers[] = array_key_exists('name', $value) ? $value['name'] : $key;
            } elseif ($value !== false) {
                $activeModifiers[] = $key;
            }
        }
        return new BemClassNames($this->fusionValue('block'), $activeModifiers);
    }
}
