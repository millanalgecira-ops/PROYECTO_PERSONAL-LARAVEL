<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles base
        Rol::create(['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Admin']);
        Rol::create(['id' => 2, 'nombre' => 'Cocina', 'descripcion' => 'Cocina']);
    }

    public function test_public_pages_are_accessible(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/registro')->assertStatus(200);
        $this->get('/carrito')->assertStatus(200);
        $this->get('/recuperar-password')->assertStatus(200);
    }

    public function test_protected_routes_redirect_unauthenticated_users(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/cocina')->assertRedirect('/login');
        $this->get('/mi-cuenta')->assertRedirect('/login');
    }

    public function test_admin_user_can_authenticate_and_access_admin_panel(): void
    {
        $admin = Usuario::create([
            'nombre' => 'Admin Test',
            'correo' => 'admin@test.com',
            'password_hash' => Hash::make('secret123'),
            'rol_id' => 1,
            'activo' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_cocina_user_can_authenticate_and_access_cocina_panel(): void
    {
        $cocina = Usuario::create([
            'nombre' => 'Cocina Test',
            'correo' => 'cocina@test.com',
            'password_hash' => Hash::make('secret123'),
            'rol_id' => 2,
            'activo' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'cocina@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/cocina');
        $this->assertAuthenticatedAs($cocina, 'web');
    }

    public function test_cliente_user_can_register_and_authenticate(): void
    {
        $response = $this->post('/registro', [
            'nombres' => 'Nuevo',
            'apellidos' => 'Cliente',
            'email' => 'cliente@test.com',
            'password' => 'cliente123',
            'confirmar_password' => 'cliente123',
        ]);

        $response->assertRedirect('/mi-cuenta');

        $cliente = Cliente::where('correo', 'cliente@test.com')->first();
        $this->assertNotNull($cliente);
        $this->assertAuthenticatedAs($cliente, 'cliente');
    }
}
