<?php

namespace Hobocta\Transactions;

class Sum
{
    /**
     * Количество знаков после запятой (для вывода и для ввода сумм)
     */
    const DECIMALS = 5;

    public static function format($sum)
    {
        return number_format($sum / pow(10, static::DECIMALS), static::DECIMALS, ',', ' ');
    }

    public static function isValidToUnFormat($sum)
    {
        return preg_match('/^\d*[,\.]?\d{1,' . Sum::DECIMALS . '}$/', $sum);
    }

    /**
     * @param $sum
     * @return int
     * @throws CommonException
     */
    public static function unFormat($sum)
    {
        if (!static::isValidToUnFormat($sum)) {
            throw new CommonException('Некорректный формат данных');
        }

        $delimiter = ',';

        $sum = str_replace('.', $delimiter, $sum);

        if (strpos($sum, $delimiter) !== false) {
            list($ceil, $decimal) = explode($delimiter, $sum);
            $ceil = (int)$ceil;
        } else {
            $ceil = (int)$sum;
            $decimal = 0;
        }

        $ceilSumm = $ceil * pow(10, static::DECIMALS);

        $decimalSumm = 0;
        for ($digit = 0; $digit < strlen($decimal); $digit++) {
            $decimalSumm += $decimal[$digit] * pow(10, static::DECIMALS - 1 - $digit);
        }

        return $ceilSumm + $decimalSumm;
    }
}
