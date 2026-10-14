<?php

namespace App;

enum AttributeDataType: string
{
    case ShortText = 'short_text';
    case LongText = 'long_text';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case Boolean = 'boolean';
    case Date = 'date';
    case SingleSelect = 'single_select';
    case MultiSelect = 'multi_select';
    case Measurement = 'measurement';
    case ColorSwatch = 'color_swatch';
    case Reference = 'reference';
}
