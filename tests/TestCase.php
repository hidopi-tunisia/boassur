<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Admin;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Connecter un admin
     * 
     * @param App\Models\Admin|null $admin
     */
    protected function signInAdmin($admin = null)
    {
        $admin = $admin ?? Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $this->user = $admin;

        return $this;
    }

    /**
     * Retourne l'url complète d'un lien sur le sous domaine admin
     * 
     * @param string  $route
     * @return string
     */
    protected function adminUrl($route)
    {
        return 'http://' . env('ADMIN_DOMAIN') . $route;
    }
}
