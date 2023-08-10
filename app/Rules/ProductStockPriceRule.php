<?php

namespace App\Rules;

use Closure;
use App\Models\Obat;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductStockPriceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $obat = array_filter($value);

        if (count($obat) == 0) {
            $fail('Silahkan pilih obat yang akan anda beli');
        }

        $DBObat = Obat::whereIn('id', $obat)->get();

        dd($value);

        $errorText = '';

        foreach ($obat as $obatId => $quantity) {
            # code...
        }
    }
}
