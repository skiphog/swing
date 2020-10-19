<?php

namespace App\Traits;

trait Travel
{
    /**
     * @param $date string
     * @return bool
     */
    protected function validateDate($date)
    {
        return filter_var($date, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '~^\d{4}-\d{2}-\d{2}$~']])
            &&
            \App\Support\Support::validateDate($date, 'Y-m-d');
    }


    /**
     * @param string|bool $date
     * @return array|bool
     */
    protected function generateDate($date = false)
    {

        if (false === $date) {
            return [
                'date_end' => date('Y-m-d')
            ];
        }

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $date = (new \DateTime($date))->modify('first day of');

        $date_now_first = (new \DateTime())->modify('first day of')->setTime(0, 0, 0);

        if ($date < $date_now_first) {
            return false;
        }

        return [
            'date_end'   => $date->format('Y-m-d') === $date_now_first->format('Y-m-d') ? date('Y-m-d') : $date->format('Y-m-d'),
            'date_start' => $date->modify('last day of')->format('Y-m-d')
        ];
    }
}
