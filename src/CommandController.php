<?php

namespace FischerEnterprise\LaravelPackageCommands;

use \Symfony\Component\Console\Application;

class CommandController
{

    public static function Register(Application $app)
    {
        // System Commands
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\InitCommand);
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\HelloCommand);

        // Make Commands
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\Make\MakeViewCommand);
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\Make\MakeModelCommand);
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\Make\MakeMigrationCommand);
        $app->add(new \FischerEnterprise\LaravelPackageCommands\Commands\Make\MakeControllerCommand);
    }

}
