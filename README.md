# color-utils
This package is intended to provide a variety of color manipulation functions similar to [those provided by SASS](http://sass-lang.com/documentation/Sass/Script/Functions.html).

## Basic Colors
There are a wide variety of options for creating colors with the `SSNepenthe\ColorUtils\Color` class:

```
namespace SSNepenthe\ColorUtils;

new Color(new Rgb(255, 255, 255));
new Color(new Rgb(255, 255, 255, 1.0));

new Color(new Hsl(0, 0, 100));
new Color(new Hsl(0, 0, 100, 1.0));

Color::fromHex('#fff');
Color::fromHex('#ffffff');
Color::fromHex('#ffffffff');

Color::fromHsl(0, 0, 100);
Color::fromHsl('hsl(0, 0%, 100%)');
Color::fromHsl(0, 0, 100, 1.0);
Color::fromHsl('hsla(0, 0%, 100%, 1.0)');

Color::fromKeyword('white');

Color::fromRgb(255, 255, 255);
Color::fromRgb('rgb(255, 255, 255)');
Color::fromRgb(255, 255, 255, 1.0);
Color::fromRgb('rgba(255, 255, 255, 1.0)');
```

Easily access each individual color attribute from any color object:

```
$color = Color::fromKeyword('white');

$color->getRed(); // int
$color->getGreen(); // int
$color->getBlue(); // int

$color->getHue(); // int
$color->getSaturation(); // int
$color->getLightness(); // int

$color->getAlpha(); // float
```

If a color matches a keyword in the CSS spec, access the keyword like so:

```
$color->getName(); // string
```

Check whether a color is light or dark:

```
$color->isLight(); // bool
$color->isDark(); // bool
```

Check whether a color is perceived as light or dark (adjusted for human sensitivity to various color components):

```
$color->looksLight(); // bool
$color->looksDark(); // bool
```

Create a new color by modifying individual components of another color:

```
$newColor = $color->with(['red' => 123, 'alpha' => 0.5]);
```

RGB and HSL components cannot be adjusted in the same operation.

## Color Transformations
The following classes provide the primary color transformations:

`SSNepenthe\ColorUtils\Transformers\AdjustColor` - Instantiate with an array of `component` => `amount` pairs. The `transform` method returns a new color with each `amount` *added to* the values of the existing color amount.

```
$color = Color::fromRgb(50, 100, 150);
$transformer = new Transformers\AdjustColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color); // 'rgb(50, 150, 175)'
```

`SSNepenthe\ColorUtils\Transformers\ChangeColor` - Instantiate with an array of `component` => `amount` pairs. The `transform` method returns a new color with each `amount` *in place of* the existing color amount.

```
$color = Color::fromRgb(50, 100, 150);
$transformer = new Transformers\ChangeColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color); // 'rgb(50, 50, 25)'
```

`SSNepenthe\ColorUtils\Transformers\Invert` - The transform method returns a new color with inverted `red`, `green`, and `blue` amounts (255 - amount).

```
$color = Color::fromRgb(50, 100, 150);
$transformer = new Transformers\Invert;
$newColor = $transformer->transform($color); // 'rgb(205, 155, 105)'
```

`SSNepenthe\ColorUtils\Transformers\Mix` - Instantiate with a color object (`$color1`) and a weight. The transform method takes a color object (`$color2`) and mixes it with `$color1`. The provided weight is applied to `$color1`. Total weight also takes the alpha values of each color into consideration as in SASS.

```
$color1 = Color::fromRgb(50, 100, 150);
$color2 = Color::fromRgb(17, 112, 84);
$transformer = new Transformers\Mix($color1, 30);
$newColor = $transformer->transform($color2); // 30/70 mix, 'rgb(27, 108, 104)'
```

`SSNepenthe\ColorUtils\Transformers\ScaleColor` - Instantiate with an array of `component` => `amount` pairs. The `transform` method determines the max possible adjustment for each `component` and modifies that `component` by `amount`% of that max.

```
$color = Color::fromRgb(50, 100, 150);
$transformer = new Transformers\ScaleColor(['green' => 50, 'blue' => 25]);
$newColor = $transformer->transform($color); // 'rgb(50, 178, 176)'
```

The following transformers are also available, though they simply use the previously mentioned primary transformers to make their own transformations:

```
* SSNepenthe\ColorUtils\Transformers\AdjustHue
* SSNepenthe\ColorUtils\Transformers\Complement
* SSNepenthe\ColorUtils\Transformers\Darken
* SSNepenthe\ColorUtils\Transformers\Desaturate
* SSNepenthe\ColorUtils\Transformers\GrayScale
* SSNepenthe\ColorUtils\Transformers\Lighten
* SSNepenthe\ColorUtils\Transformers\Opacify
* SSNepenthe\ColorUtils\Transformers\Saturate
* SSNepenthe\ColorUtils\Transformers\Shade
* SSNepenthe\ColorUtils\Transformers\Tint
* SSNepenthe\ColorUtils\Transformers\Transparentize
```

## Conditional Transformers
You can conditionally apply a transformation to a color using `SSNepenthe\ColorUtils\Transformers\ConditionalTransformer`:

```
$transformer = new Transformers\ConditionalTransformer(function (Color $color) : bool {
    return $color->isDark();
}, new Transformers\Lighten(30));

$transformer->transform(Color::fromKeyword('white')); // Unmodified, 'rgb(255, 255, 255)'

$transformer->transform(Color::fromKeyword('black')); // Lightened by 30%, rgb(77, 77, 77)
```

## Transformer Pipeline
Create transformation pipelines using `SSNepenthe\ColorUtils\Transformers\TransformerPipeline`:

```
$pipeline = new Transformers\TransformerPipeline;
$pipeline->add(new Transformers\Desaturate(15));
$pipeline->add(new Transformers\ConditionalTransformer(function ($color) {
    return $color->looksLight();
}, new Transformers\Darken(5)));

$pipeline->transform(Color::fromKeyword('red'));
```

Entire pipelines can be reused within other pipelines:

```
$pipeline2 = new Transformers\TransformerPipeline;
$pipeline2->add(new Transformers\Complement);
$pipeline->add(new Transformers\ConditionalTransformer(function ($color) {
    return $color->getRed() > 100;
}, new Transformers\AdjustColor(['red' => -100])));
$pipeline2->add($pipeline);

$pipeline2->transform(Color::fromKeyword('red'));
```
