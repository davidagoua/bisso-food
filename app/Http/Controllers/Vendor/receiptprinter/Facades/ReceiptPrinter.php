<?php

namespace App\Http\Controllers\Vendor\ReceiptPrinter\Facades;

use Illuminate\Support\Facades\Facade;

class ReceiptPrinter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'receiptprinter';
    }
}
