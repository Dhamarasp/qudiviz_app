<?php

namespace App\Helpers;

class ToastHelper
{
    /**
     * Flash a success toast message
     *
     * @param string $message
     * @return void
     */
    public static function success($message)
    {
        session()->flash('success', $message);
    }

    /**
     * Flash an error toast message
     *
     * @param string $message
     * @return void
     */
    public static function error($message)
    {
        session()->flash('error', $message);
    }

    /**
     * Flash a warning toast message
     *
     * @param string $message
     * @return void
     */
    public static function warning($message)
    {
        session()->flash('warning', $message);
    }

    /**
     * Flash an info toast message
     *
     * @param string $message
     * @return void
     */
    public static function info($message)
    {
        session()->flash('info', $message);
    }
}
