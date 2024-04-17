<?php

namespace App\Helpers;

use NumberFormatter;

abstract class FormatterHelper
{
    public static function onlyNumbers(mixed $value): ?string
    {
        return preg_replace('/\D/', '', $value);
    }

    public static function cpfCnpj(mixed $value): string
    {
        $value = self::onlyNumbers($value);

        if ($value) {
            if (strlen($value) === 11) {
                return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
            }

            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
        }

        return '';
    }

    public static function cep(mixed $value): string
    {
        $value = self::onlyNumbers($value);

        if (strlen($value) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
        }

        return '';
    }

    public static function date(mixed $value): string
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    public static function oldDate(mixed $value): string
    {
        $value = self::onlyNumbers($value);

        return substr($value, 6, 2).substr($value, 4, 2).substr($value, 0, 4);
    }

    public static function money(mixed $value, bool $currency = false): string
    {
        $currency = new NumberFormatter(
            'pt_BR',
            $currency
                ? NumberFormatter::CURRENCY
                : NumberFormatter::DECIMAL
        );
        $currency->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        return $currency->format($value);
    }

    public static function decimal(mixed $value, int $afterComma = 2): string
    {
        if (is_numeric($value) && floor($value) !== $value) {
            return $value;
        }

        if ($value === '' || is_null($value)) {
            $value = '0.00';
        }

        $value = str_replace(['.', ','], ['', '.'], $value);

        return number_format($value, $afterComma, '.', '');
    }

    public static function phone(mixed $value): string
    {
        $value = self::onlyNumbers($value);

        if ($value) {
            if (strlen($value) === 10) {
                return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $value);
            }

            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $value);
        }

        return '';
    }
}
