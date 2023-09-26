<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Settings\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidFile implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_file($value)) {
            $fail('bt.pdf_driver_wkhtmltopdf')->translate();
        }
    }
}
