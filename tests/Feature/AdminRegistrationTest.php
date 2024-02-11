<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function la_page_contient_un_composant_livewire()
    {
        $this->signInAdmin();
        $this->get($this->adminUrl('/admin/ajout'))->assertSeeLivewire('admin.admin.register');
    }

    /** @test */
    function peut_creer_un_admin()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi@imiary.test')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertRedirect('/');

        $this->assertTrue(Admin::where('email', '=', 'boozi@imiary.test')->exists());

        $this->assertEquals('boozi@imiary.test', auth('admin')->user()->email);
    }

    /** @test */
    function nom_est_obligatoire()
    {
        Livewire::test('admin.admin.register')
            ->set('name', '')
            ->set('email', 'boozi@imiary.test')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    function email_est_obligatoire()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', '')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    function email_est_valide()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    function email_est_unique()
    {
        Admin::create(['name' => 'zoomi', 'email' => 'boozi@imiary.test', 'password' => 'secret123']);

        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi@imiary.test')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    function mot_de_passe_obligatoire()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi@imiary.test')
            ->set('password', '')
            ->set('passwordConfirmation', 'secret123')
            ->call('register')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    function mot_de_passe_doit_faire_8_caracteres_ou_plus()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi@imiary.test')
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('register')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    function mot_de_passe_doit_etre_confirme()
    {
        Livewire::test('admin.admin.register')
            ->set('name', 'boozi')
            ->set('email', 'boozi@imiary.test')
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret456')
            ->call('register')
            ->assertHasErrors(['password' => 'same']);
    }
}
