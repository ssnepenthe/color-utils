# color-utils
This package is intended to provide a variety of color manipulation functions similar to [those found in SASS](http://sass-lang.com/documentation/Sass/Script/Functions.html).

## Requirements
PHP 7.0 or later.

## Installation
Although this package has no dependencies, it does rely on the Composer autoloader and should therefore be installed using Composer:

```
composer require ssnepenthe/color-utils
```

## Usage

### Color Representation
Start by creating a new `SSNepenthe\ColorUtils\Color` object:

```php
use SSNepenthe\ColorUtils\Color;

$color = Color::fromHsl(int $h, int $s, int $l, [float $a]);
$color = Color::fromRgb(int $r, int $g, int $b, [float $a]);

// $s can be any of the following: color keyword, rgb hex notation, rgb functional notation,
// rgba hex notation, rgba functional notation, hsl functional notation and hsla functional notation.
// More info: https://developer.mozilla.org/en-US/docs/Web/CSS/color_value
$color = Color::fromString(string $s);
```

Or use the functional counterparts:

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\hsl;

$color = hsl(int $h, int $s, int $l, [float $a]);
$color = rgb(int $r, int $g, int $b, [float $a]);

// $s can be any of the string formats mentioned above.
$color = hsl(string $s);
$color = rgb(string $s);
```

Easily access individual components from any color object:

```php
// All values are of type int except alpha, which is float.
$color->getRed();
$color->getGreen();
$color->getBlue();

$color->getHue();
$color->getSaturation();
$color->getLightness();

$color->getAlpha();
```

*OR*

```php
use function SSNepenthe\ColorUtils\hue;
use function SSNepenthe\ColorUtils\red;
use function SSNepenthe\ColorUtils\blue;
use function SSNepenthe\ColorUtils\alpha;
use function SSNepenthe\ColorUtils\green;
use function SSNepenthe\ColorUtils\lightness;
use function SSNepenthe\ColorUtils\saturation;

red($color);
green($color);
blue($color);

hue($color);
saturation($color);
lightness($color);

alpha($color);
```

Conversion between RGB and HSL (and vice versa) is automatic.

If a color has a corresponding keyword in the CSS spec, you can access that keyword like so:

```php
// String.
$color->getName();
```

*OR*

```php
use function SSNepenthe\ColorUtils\name;

name($color);
```

Check whether a color is light or dark:

```php
// Bool.
$color->isLight();
$color->isDark();
```

*OR*

```php
use function SSNepenthe\ColorUtils\is_light;
use function SSNepenthe\ColorUtils\is_dark;

is_light($color);
is_dark($color);
```

Check whether a color is perceived as light or dark (adjusted for human sensitivity to various color components):

```php
// Bool.
$color->looksLight();
$color->looksDark();
```

*OR*

```php
use function SSNepenthe\ColorUtils\looks_light;
use function SSNepenthe\ColorUtils\looks_dark;

looks_light($color);
looks_dark($color);
```

You can also set a custom threshold for each of these brightness checks (on a scale from 0 - 100, default is 50):

```php
$color->isLight(30);
```

*OR*

```php
use function SSNepenthe\ColorUtils\is_light;

is_light($color, 30);
```

### Color Transformations
The following primary transformations are available:

**Adjust Color**:

Provide an array of `component` => `amount` pairs. Components can be any of `hue`, `saturation`, `lightness`, `red`, `green`, `blue` or `alpha`.

This transformation creates a new color where each `amount` has been *added to* the values of the existing color amount.

The following examples create the color `rgb(50, 150, 175)`.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\AdjustColor;

$color = Color::fromRgb(50, 100, 150);
$transformer = new AdjustColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color);
```

*OR*

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\adjust_color;

$color = rgb(50, 100, 150);
$newColor = adjust_color($color, ['green' => 50, 'blue' => 25]);
```

**Change Color**

Provide an array of `component` => `amount` pairs.

This transformation creates a new color where each `amount` is used *in place of* the existing color amount.

The following examples create the color `rgb(50, 50, 25)`.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ChangeColor;

$color = Color::fromRgb(50, 100, 150);
$transformer = new ChangeColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color);
```

*OR*

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\change_color;

$color = rgb(50, 100, 150);
$newColor = change_color($color, ['green' => 50, 'blue' => 25]);
```

**Invert Color**

This transformation creates a new color where the values of `red`, `green`, and `blue` have been inverted (subtracted from the max 255).

The following examples create the color `rgb(205, 155, 105)`.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Invert;

$color = Color::fromRgb(50, 100, 150);
$transformer = new Invert;
$newColor = $transformer->transform($color);
```

*OR*

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\invert;

$color = rgb(50, 100, 150);
$newColor = invert($color);
```

**Mix Color**

Provide two color objects and a weight between 0 and 100.

This transformation creates a new color by mixing `weight`% of `color1` with 100 - `weight`% of `color2`. The alpha values of each color are also factored in to the transformation.

The following examples create the color `rgb(27, 108, 104)`.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Mix;

$color1 = Color::fromRgb(50, 100, 150);
$color2 = Color::fromRgb(17, 112, 84);
$transformer = new Mix($color1, 30);
$newColor = $transformer->transform($color2);
```

*OR*

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\mix;

$color1 = rgb(50, 100, 150);
$color2 = rgb(17, 112, 84);
$newColor = mix($color1, $color2, 30);
```

**Scale Color**

Provide an array of `component` => `amount` pairs.

This transformation creates a new color where the maximum possible adjustment for each `component` is modified by `amount`% of that maximum.

The following examples create the color `rgb(50, 178, 176)`.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ScaleColor;

$color = Color::fromRgb(50, 100, 150);
$transformer = new ScaleColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color);
```

*OR*

```php
use function SSNepenthe\ColorUtils\rgb;
use function SSNepenthe\ColorUtils\scale_color;

$color = rgb(50, 100, 150);
$newColor = scale_color($color, ['green' => 50, 'blue' => 25]);
```

There are also functions for each of the following transformations, though they simply use the previously mentioned transformers to make their own color modifications:

* Adjust Hue
* Complement
* Darken
* Desaturate
* Gray Scale
* Lighten
* Opacify
* Saturate
* Shade
* Tint
* Transparentize

#### Conditional Transformers
You can conditionally apply a transformation to a color using `SSNepenthe\ColorUtils\Transformers\ConditionalTransformer`:

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

$transformer = new ConditionalTransformer(function (Color $color) : bool {
    return $color->isDark();
}, new Lighten(30));

$transformer->transform(Color::fromString('white')); // Unmodified, 'rgb(255, 255, 255)'

$transformer->transform(Color::fromString('black')); // Lightened by 30%, 'rgb(77, 77, 77)'
```

Pass another transformer object as the optional third parameter to apply a transformation in the case where the callable returns false.

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Darken;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

$transformer = new ConditionalTransformer(function ($color) {
    return $color->isDark();
}, new Lighten(30), new Darken(15));

$transformer->transform(Color::fromString('white')); // Darkened by 15%, 'rgb(217, 217, 217)'

$transformer->transform(Color::fromString('black')); // Lightened by 30%, 'rgb(77, 77, 77)'
```

#### Transformer Pipeline
Create transformation pipelines using `SSNepenthe\ColorUtils\Transformers\TransformerPipeline`:

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Darken;
use SSNepenthe\ColorUtils\Transformers\Desaturate;
use SSNepenthe\ColorUtils\Transformers\TransformerPipeline;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

$pipeline = new TransformerPipeline;
$pipeline->add(new Desaturate(15));
$pipeline->add(new ConditionalTransformer(function ($color) {
    return $color->looksLight();
}, new Darken(5)));

$pipeline->transform(Color::fromString('red'));
```

Entire pipelines can be reused as transformers in other pipelines:

```php
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Complement;
use SSNepenthe\ColorUtils\Transformers\AdjustColor;
use SSNepenthe\ColorUtils\Transformers\TransformerPipeline;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

$pipeline2 = new TransformerPipeline;
$pipeline2->add(new Complement);
$pipeline2->add(new ConditionalTransformer(function ($color) {
    return $color->getRed() > 100;
}, new AdjustColor(['red' => -100])));
$pipeline2->add($pipeline);

$pipeline2->transform(Color::fromString('red'));
```

Transformers are called in the order they were added to the pipeline and receive the color object returned from the previous transformation.
