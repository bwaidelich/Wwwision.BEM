# Wwwision.BEM

BEM style classes (see http://getbem.com/) with Neos Fusion

## Installation

```
composer require wwwision/bem 
```

(Alternatively feel free to copy and adjust the parts that you need)

## Usage

### Eel Helper

This package provides a simple Eel-Helper `BEM.block()` that can be used to
render BEM-style class names:

```
${BEM.block('some-component')} // => "some-component"
${BEM.block('some-component', ['foo', 'bar'])} // => "some-component some-component--foo some-component--bar"
${BEM.block('some-component').element('some-element')} // => "some-component__some-element"
${BEM.block('some-component').extend('sub')} // => "some-component-sub"
```

### Fusion Prototype

For more advanced usage the Fusion Prototypes `Wwwision.BEM:Block` and `Wwwision.BEM:Modifier` are provided
that allow to reuse and extend BEM-style classes more easily:

```
root = Wwwision.BEM:Block {
    block = 'some-block'
    modifiers {
        wide = Wwwision.BEM:Modifier {
            name = 'wide'
            active = ${isWide}
        }
    }
}
```

The above will render `some-block some-block--wide`.
For fixed modifier names, the syntax can be condensed to:

```
root = Wwwision.BEM:Block {
    block = 'some-block'
    modifiers {
        'wide' = ${isWide}
    }
}
```

### Use with AFX Components

The helpers are especially useful in conjunction with AFX Components:

```
prototype(SomeComponent) < prototype(Neos.Fusion:Component) {

    header = ''
    content = ''
    level = 1

    renderer.@context {
        class = Wwwision.BEM:Block {
            block = 'some-block'
            modifiers {
                'foo' = true
                dynamic = Wwwision.BEM:Modifier {
                    name = ${'level-' + props.level}
                }
            }
        }
    }
    renderer = afx`
        <section class={class}>
            <h1 class={class.element('header')}>{props.header}</h1>
            <p class={class.element('content')}>{props.content}</p>
            <div class={class.extend('nested')}>...</div>
        </section>
    `
}
```

This would result in the following markup:

```html

<section class="some-block some-block--foo some-block--level-1">
    <h1 class="some-block__header">the head</h1>
    <p class="some-block__content">the content</p>
    <div class="some-block-nested">...</div>
</section>
```

License
-------

Licensed under MIT, see [LICENSE](LICENSE)
