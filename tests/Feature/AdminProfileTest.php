<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signInAdmin(Admin::factory()->create());
    }

    /** @test */
    function la_page_contient_un_composant_livewire()
    {
        $this->get($this->adminUrl('/admin/edition/1'))->assertSeeLivewire('admin.admin.edit');
    }

    /** @test */
    function peut_editer_un_admin()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('name', 'foo')
            ->set('email', 'foo@bar.com')
            ->set('password', 'minimaxi')
            ->set('passwordConfirmation', 'minimaxi')
            ->call('update');

        $this->user->refresh();
        $this->assertEquals('foo', $this->user->name);
        $this->assertEquals('foo@bar.com', $this->user->email);
        $this->assertTrue(Hash::check('minimaxi', $this->user->password));
    }

    /** @test */
    function le_formulaire_d_edition_est_prerempli()
    {
        $user = Admin::create(['name' => 'foo', 'email' => 'foo@bar.com', 'password' => 'secret123']);

        Livewire::actingAs($user, 'admin')
            ->test('admin.admin.edit', ['admin' => $user])
            ->assertSet('name', 'foo')
            ->assertSet('email', 'foo@bar.com');
    }

    /** @test */
    function nom_est_obligatoire()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('name', '')
            ->call('update')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    function email_est_obligatoire()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('email', '')
            ->call('update')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    function email_est_valide()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('email', 'boozi')
            ->call('update')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    function email_est_unique()
    {
        Admin::create(['name' => 'zoomi', 'email' => 'boozi@imiary.test', 'password' => 'secret123']);

        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('email', 'boozi@imiary.test')
            ->call('update')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    function mot_de_passe_doit_faire_8_caracteres_ou_plus()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('update')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    function mot_de_passe_doit_etre_confirme()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->set('password', 'secret123')
            ->set('passwordConfirmation', 'secret456')
            ->call('update')
            ->assertHasErrors(['password' => 'same']);
    }

    /** @test */
    function un_message_est_affiche_apres_enregistrement()
    {
        Livewire::test('admin.admin.edit', ['admin' => $this->user])
            ->call('update')
            ->assertEmitted('notify-saved');
    }
}
