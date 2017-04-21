# color-utils
This package is intended to provide a variety of [SASS-like color manipulation functions](http://sass-lang.com/documentation/Sass/Script/Functions.html).

## Requirements
Composer, PHP 7.0 or later.

## Installation
Install using Composer:

```
composer require ssnepenthe/color-utils
```

## Usage
*All functions listed are within the `SSNepenthe\ColorUtils` namespace.*

### Color Representation
Create Color objects using the `color` function:

**color(array $color)**

`$color` is an array of `$channel => $value` pairs. Valid channels are red, green, blue, hue, saturation, lightness and alpha.

* `color(['red' => 255, 'green' => 0, 'blue' => 51])`
* `color(['hue' => 348, 'saturation' => 100, 'lightness' => 50, 'alpha' => 0.7])`

**color(string $color)**

`$color` is a string representation of a color in one of the following formats:

* Hex notation: `'#f03'` or `'#ff0033'`
* Keyword notation: `'white'` ([list of valid keywords on MDN](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value#Color_keywords))
* Functional hsl(a) notation: `'hsl(348, 100%, 50%)` or `'hsla(348, 100%, 50%, 0.7)'`
* Functional rgb(a) notation: `'rgb(255, 0, 51)'` or `'rgba(255, 0, 51, 0.7)'`

**color(int $red, int $green, int $blue, [float $alpha])**

0 - 255 range for each of `$red`, `$green` and `$blue`, 0 - 1 for `$alpha`.

* `color(255, 0, 51)`
* `color(255, 0, 51, 0.7)`

**color(float $hue, float $saturation, float $lightness, [float $alpha])**

0 - 360 range for `$hue`, 0 - 100 for each of `$saturation` and `$lightness`, 0 - 1 for `$alpha`.

* `color(348.0, 100.0, 50.0)`
* `color(348.0, 100.0, 50.0, 0.7)`

*Regarding the previous two examples:*

The values 255, 0 and 51 could technically represent RGB values as well as HSL values. In cases like this, RGB takes precedence over HSL.

If you need finer control, use the following functions:

**hsl(float $hue, float $saturation, float $lightness)**

**hsla(float $hue, float $saturation, float $lightness, float $alpha)**

**rgb(int $red, int $green, int $blue)**

**rgba(int $red, int $green, int $blue, float $alpha)**

Lastly, the `hsla` and `rgba` functions can also be used to adjust the transparency of an existing color:

```php
$hsl = hsl(348, 100, 50);
echo $hsl; // 'hsl(348, 100%, 50%)'

$hsla = hsla($hsl, 0.7);
echo $hsla; // 'hsla(348, 100%, 50%, 0.7)'
```

### Color Components
Individual color components are accessible using the following functions (which each accept any `$color` argument recognized by the `color` function, including a complete color object):

**alpha($color)**

Get the alpha channel of a color.

* `alpha('#f03'); // 1.0`

**blue($color)**

Get the blue channel of a color.

* `blue('#f03'); // 51`

**brightness($color)**

Calculates [color brightness](https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 (black) to 255 (white).

* `brightness('#f03') // 82.059`

**green($color)**

Get the green channel of a color.

* `green('#f03'); // 0`

**hue($color)**

Get the hue channel of a color.

* `hue('#f03'); // 348.0`

**is_bright($color)**

Accepts an optional `$threshold` float as the last parameter with a default of 127.5. Checks `brightness($color) >= $threshold`.

* `is_bright('#f03'); // false`
* `is_bright('#f03', 82); // true`

**is_light($color)**

Accepts an optional `$threshold` float as the last parameter with a default of 50.0. Checks `lightness($color) >= $threshold`.

* `is_light('#f03'); // true`
* `is_light('#f03', 55); // false`

**lightness($color)**

Get the lightness channel of a color.

* `lightness('#f03'); // 50.0`

**looks_bright($color)**

Accepts an optional `$threshold` float as the last parameter with a default of 127.5. Checks `perceived_brightness($color) >= $threshold`.

* `looks_bright('#f03'); // true`
* `looks_bright('#f03', 141.0); // false`

**name($color)**

Get the name (keyword) representation of a color. Returns an empty string if none is found.

* `name('#f03'); // ''`
* `name('#00f'); // 'blue'`

**opacity($color)**

Alias of `alpha($color)`.

**perceived_brightness($color)**

Calculates the [perceived brightness](http://alienryderflex.com/hsp.html) of a color on a scale from 0 (black) to 255 (white).

* `perceived_brightness('#f03'); // 140.49551`

**red($color)**

Get the red channel of a color.

* `red('#f03'); // 255`

**relative_luminance($color)**

Calculates the [relative luminance](https://www.w3.org/TR/WCAG20/#relativeluminancedef) of a color on a scale from 0 (black) to 1 (white).

* `relative_luminance('#f03'); // 0.21499`

**saturation($color)**

Get the saturation channel of a color.

* `saturation('#f03'); // 100.0`

### Color Calculations
The following functions calculate differences between two given colors:

**brightness_difference(Color $color1, Color $color2)**

Calculates [brightness difference](https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 to 255.

* `brightness_difference(color('red'), color('green')) // 1.109`

**color_difference(Color $color1, Color $color2)**

Calculates [color difference](https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 to 765.

* `color_difference(color('red'), color('green')) // 383`

**constrast_ratio(Color $color1, Color $color2)**

Calculates the [contrast ratio](https://www.w3.org/TR/WCAG20/#contrast-ratiodef) between two colors on a scale from 1 to 21.

* `contrast_ratio(color('red'), color('green')) // 1.28486`

### Color Transformations
Colors can be transformed using the following functions (all accept any `$color` argument recognized by the `color` function):

**adjust_color($color, array $channels)**

Creates a new color by increasing/decreasing one or more channel values of `$color`. This can change red, green, blue, hue, saturation, lightness and alpha channels. `$channels` are specified as an array of `$channel => $amount` pairs.

* `(string) adjust_color('rgb(50, 100, 150)', ['green' => 50, 'blue' => -50]); // 'rgb(50, 150, 100)'`

**change_color($color, array $channels)**

Creates a new color by changing one or more channel values of `$color`. This can change red, green, blue, hue, saturation, lightness and alpha channels. `$channels` are specified as an array of `$channel => $amount` pairs.

* `(string) change_color('rgb(50, 100, 150)', ['green' => 50, 'blue' => 25]); // 'rgb(50, 50, 25)'`

**invert($color)**

Creates a new color by inverting (subtracting from 255) the red, green and blue channels of `$color`. Alpha is left unchanged.

* `(string) invert('rgb(50, 100, 150)'); // 'rgb(205, 155, 105)'`

**mix(Color $color1, Color $color2, int $weight = 50)**

Creates a new color by averaging the red, green and blue channels from `$color1` and `$color2`, with `$color1` optionally weighted by `$weight`%. Alpha is also considered. [Uses the same algorithm as SASS](https://github.com/sass/sass/blob/stable/lib/sass/script/functions.rb#L1291).

* `(string) mix(color('rgb(50, 100, 150)'), color('rgb(100, 100, 100)')); // 'rgb(75, 100, 125)`
* `(string) mix(color('rgb(50, 100, 150)'), color('rgb(100, 100, 100)'), 25); // 'rgb(88, 100, 113)`
* `(string) mix(color('rgb(50, 100, 150)'), color('rgb(100, 100, 100)'), 75); // 'rgb(63, 100, 138)`

**scale_color($color, array $channels)**

Creates a new color by scaling one or more channel values of `$color`. This can change red, green, blue, hue, saturation, lightness and alpha channels. `$channels` are specified as an array of `$channel => $percent` pairs, and each channel value is scaled by `$percent`% of the max possible adjustment.

In the example below, green is 100 and we want to scale positively by 50%. The maximum allowed value is 255 which means the maximum possible adjustment is 155. The new green value then becomes 100 + (155 * 0.5).

Likewise, blue is 150 and we want to scale negatively by 50%. The minimum allowed value is 0 which means the maximum possible adjustment is -150. The new blue value then becomes 150 + (-150 * 0.5).

* `(string) scale_color('rgb(50, 100, 150)', ['green' => 50, 'blue' => -50]); // 'rgb(50, 178, 75)'`

**adjust_hue($color, float $degrees)**

Alias of `adjust_color($color, ['hue' => $degrees])`.

**complement($color)**

Alias of `adjust_color($color, ['hue' => 180])`.

**darken($color, float $amount)**

Alias of `adjust_color($color, ['lightness' => -1 * $amount])`.

**desaturate($color, float $amount)**

Alias of `adjust_color($color, ['saturation' => -1 * $amount])`.

**fade_in($color, float $amount)**

Alias of `opacify($color, $amount)`.

**fade_out($color, float $amount)**

Alias of `transparentize($color, $amount)`.

**grayscale($color)**

Alias of `change_color($color, ['saturation' => 0])`.

**lighten($color, float $amount)**

Alias of `adjust_color($color, ['lightness' => $amount])`.

**opacify($color, float $amount)**

Alias of `adjust_color($color, ['alpha' => $amount])`.

**saturate($color, float $amount)**

Alias of `adjust_color($color, ['saturation' => $amount])`.

**shade($color, int $weight = 50)**

Alias of `mix(color('black'), color($color), $weight)`.

**tint($color, int $weight = 50)**

Alias of `mix(color('white'), color($color), $weight)`.

**transparentize($color, float $amount)**

Alias of `adjust_color($color, ['alpha' => -1 * $amount])`.
