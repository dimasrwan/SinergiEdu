<?php

use App\Providers\AppServiceProvider;
use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;

return [
    AppServiceProvider::class,
    DomPDFServiceProvider::class,
    ExcelServiceProvider::class,
];
