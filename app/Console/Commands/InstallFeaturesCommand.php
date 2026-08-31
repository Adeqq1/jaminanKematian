<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallFeaturesCommand extends Command
{
    protected $signature = 'install:features {--answers=}';
    protected $description = 'Choose which starter kit features to keep';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
