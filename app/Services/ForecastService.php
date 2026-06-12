<?php

namespace App\Services;

use InvalidArgumentException;

class ForecastService
{
    public function calculateSMA(array $data, int $window = 3): float
    {
        if (count($data) < $window) {
            throw new InvalidArgumentException('Data harus berisi minimal ' . $window . ' nilai.');
        }

        $slice = array_slice($data, -$window);

        return array_sum($slice) / $window;
    }

    public function calculateWMA(array $data, array $weights = [1, 2, 3]): float
    {
        $window = count($weights);

        if (count($data) < $window) {
            throw new InvalidArgumentException('Data harus berisi minimal ' . $window . ' nilai.');
        }

        $slice = array_slice($data, -$window);
        $totalWeight = array_sum($weights);
        $total = 0;

        foreach ($slice as $index => $value) {
            $total += $value * $weights[$index];
        }

        return $total / $totalWeight;
    }

    public function calculateMAD(array $actual, array $forecast): float
    {
        if (count($actual) !== count($forecast) || count($actual) === 0) {
            return 0;
        }

        $total = 0;
        foreach ($actual as $index => $value) {
            $total += abs($value - $forecast[$index]);
        }

        return $total / count($actual);
    }

    public function calculateMAPE(array $actual, array $forecast): float
    {
        if (count($actual) !== count($forecast) || count($actual) === 0) {
            return 0;
        }

        $total = 0;
        $nonZeroCount = 0;

        foreach ($actual as $index => $value) {
            if ($value === 0) {
                continue;
            }

            $total += abs(($value - $forecast[$index]) / $value);
            $nonZeroCount++;
        }

        return $nonZeroCount === 0 ? 0 : ($total / $nonZeroCount) * 100;
    }

    public function buildForecastHistory(array $actual, string $method, array $weights = [1, 2, 3], int $window = 3): array
    {
        $forecastPoints = [];
        $startIndex = $window;

        while (count($actual) >= $window && $startIndex < count($actual)) {
            $windowData = array_slice($actual, $startIndex - $window, $window);

            if ($method === 'wma') {
                $forecastPoints[] = $this->calculateWMA($windowData, $weights);
            } else {
                $forecastPoints[] = $this->calculateSMA($windowData, $window);
            }

            $startIndex++;
        }

        $errors = array_slice($actual, $window);

        return [
            'forecast_points' => $forecastPoints,
            'mad' => $this->calculateMAD($errors, $forecastPoints),
            'mape' => $this->calculateMAPE($errors, $forecastPoints),
        ];
    }

    public function nextForecast(array $actual, string $method, array $weights = [1, 2, 3], int $window = 3): float
    {
        if ($method === 'wma') {
            return $this->calculateWMA($actual, $weights);
        }

        return $this->calculateSMA($actual, $window);
    }
}
