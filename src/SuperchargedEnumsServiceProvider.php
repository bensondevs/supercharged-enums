<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use Illuminate\Support\ServiceProvider;

class SuperchargedEnumsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../stubs/enum.backed.stub' => base_path('stubs/enum.backed.stub'),
        ], 'supercharged-enums-stubs');
    }
}
