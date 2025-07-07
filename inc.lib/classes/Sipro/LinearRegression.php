<?php

namespace Sipro;
use Sipro\Exception\RegressionException;

/**
 * LinearRegression is a class that implements simple linear regression.
 *
 * This class allows you to train a linear regression model using x and y data,
 * predict y values from x, predict x values from y, and retrieve the model parameters.
 *
 * @package Sipro
 */
class LinearRegression
{
    const NOT_TRAINED = "Model has not been trained yet. Please call train() method first.";
    
    private $coefficient = 0;
    private $intercept = 0;
    private $trained = false;

    /**
     * LinearRegression constructor.
     * Initializes the model with default values.
     */
    public function train(array $x_data, array $y_data)
    {
        $n = count($x_data);
        if ($n !== count($y_data) || $n === 0) {
            throw new RegressionException("x and y data must have the same length and cannot be empty.");
        }

        $sum_x = array_sum($x_data);
        $sum_y = array_sum($y_data);
        $sum_xy = 0;
        $sum_x2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sum_xy += $x_data[$i] * $y_data[$i];
            $sum_x2 += $x_data[$i] * $x_data[$i];
        }

        $mean_x = $sum_x / $n;
        $mean_y = $sum_y / $n;

        $numerator = $sum_xy - $n * $mean_x * $mean_y;
        $denominator = $sum_x2 - $n * $mean_x * $mean_x;

        if ($denominator == 0) {
            throw new RegressionException("Cannot calculate regression: division by zero.");
        }

        $this->coefficient = $numerator / $denominator;
        $this->intercept = $mean_y - $this->coefficient * $mean_x;
        $this->trained = true;
    }

    /**
     * Checks if the model has been trained.
     *
     * @return bool True if the model is trained, false otherwise.
     */
    public function getYFromX($x)
    {
        if (!$this->trained) {
            throw new RegressionException(self::NOT_TRAINED);
        }

        return $this->coefficient * $x + $this->intercept;
    }

    /**
     * Predicts the value of x given a value of y.
     *
     * @param float $y The value of y to predict x from.
     * @return float The predicted value of x.
     * @throws RegressionException If the model has not been trained or if the coefficient is zero.
     */
    public function getXFromY($y)
    {
        if (!$this->trained) {
            throw new RegressionException(self::NOT_TRAINED);
        }

        if ($this->coefficient == 0) {
            throw new RegressionException("Coefficient cannot be zero to calculate x from y.");
        }

        return ($y - $this->intercept) / $this->coefficient;
    }

    /**
     * Returns the parameters of the trained model.
     *
     * @return array An associative array containing 'coefficient' and 'intercept'.
     * @throws RegressionException If the model has not been trained.
     */
    public function getParameters()
    {
        if (!$this->trained) {
            throw new RegressionException(self::NOT_TRAINED);
        }

        return [
            'coefficient' => $this->coefficient,
            'intercept' => $this->intercept
        ];
    }
}
