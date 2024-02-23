<?php

namespace HabibAlkhabbaz\IdentityDocuments\Tests;

use HabibAlkhabbaz\IdentityDocuments\IdentityDocumentsServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use WithWorkbench;

    protected function getPackageProviders($app)
    {
        return [IdentityDocumentsServiceProvider::class];
    }
}
