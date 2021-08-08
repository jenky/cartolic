<?php

namespace Jenky\Cartolic\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartolic:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Cartolic resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Cartolic Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'cartolic-provider']);

        $this->comment('Publishing Cartolic Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'cartolic-config']);

        $this->registerCartolicServiceProvider();

        $this->info('Cartolic scaffolding installed successfully.');
    }

    /**
     * Register the Cartolic service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerCartolicServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\CartolicServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\CartolicServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/CartolicServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/CartolicServiceProvider.php'))
        ));
    }
}
