<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('introduce', function () {
    $this->comment('Mam na imię Wojciech i uczę się tworzenia aplikacji w Laravel. Nie mam doświadczenia w branży IT, ale mam nadzieję je zdobyć.');
})->purpose('Introduce me');
