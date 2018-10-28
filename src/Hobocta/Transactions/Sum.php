<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Config\Config;

class Sum
{
    private $decimals;

    public function __construct(Config $config)
    {
        $this->decimals = (int)$config->get('balance')['decimals'];
    }

    public function format(int $sum): string
    {
        return number_format(
            $sum / pow(10, $this->decimals),
            $this->decimals,
            ',',
            ' '
        );
    }

    /**
     * @param string $sum
     * @return float|int
     * @throws CommonException
     */
    public function unFormat(string $sum)
    {
        if (!$this->isValidToUnFormat($sum)) {
            throw new CommonException('Некорректный формат данных', ['sum' => $sum]);
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

        $ceilSumm = $ceil * pow(10, $this->decimals);

        $decimalSumm = 0;
        if ($decimal) {
            for ($digit = 0; $digit < strlen($decimal); $digit++) {
                $decimalSumm += (int)$decimal[$digit] * pow(10, $this->decimals - 1 - $digit);
            }
        }

        return $ceilSumm + $decimalSumm;
    }

    public function isValidToUnFormat(string $sum): bool
    {
        return preg_match('/^\d*[,\.]?\d{1,' . $this->decimals . '}$/', $sum);
    }
}
