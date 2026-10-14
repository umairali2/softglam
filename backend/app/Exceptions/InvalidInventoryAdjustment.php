<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;
use RuntimeException;

class InvalidInventoryAdjustment extends RuntimeException implements ShouldntReport {}
