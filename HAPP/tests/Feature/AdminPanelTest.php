<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_is_not_publically_accessible(): void
    {
        $response = $this->get('/admin_panel');
        $response->assertStatus(302);
    }

    public function test_admin_panel_is_not_accessible_for_normal_user(): void
    {

        $normalUser = User::create([
            'name' => 'normalUser',
            'password' => 'heslo',
            'email' => 'email@email.com'
        ]);

        $response = $this->actingAs($normalUser)->get('/admin_panel');

        $response->assertStatus(302);
    }

    public function test_admin_panel_is_accessible_for_admin(): void
    {

        $adminUser = User::create([
            'name' => 'admin',
            'password' => 'heslo',
            'email' => 'admin@email.com',
            'is_admin' => '1'
        ]);

        $response = $this->actingAs($adminUser)->get('/admin_panel');

        $response->assertStatus(200);
    }

    public function test_user_list_is_returned(): void
    {
        $adminUser = User::create([
            'name' => 'admin',
            'password' => 'heslo',
            'email' => 'admin@email.com',
            'is_admin' => '1'
        ]);


        $response = $this->actingAs($adminUser)->json('GET', '/AP/users')
            ->assertStatus(200)
            ->assertJson([
                [ 'name' => 'admin']
            ]);
    }

    public function test_add_region_to_regions_table(){

        $adminUser = User::create([
            'name' => 'admin',
            'password' => 'heslo',
            'email' => 'admin@email.com',
            'is_admin' => '1'
        ]);

        $response = $this->actingAs($adminUser)->post('/AP/addRegion', [ 'name' => 'new region']);

        $this->assertDatabaseHas('regions',[ 'name' => 'new region']);
    }

    public function test_update_region_in_regions_table(){

        $adminUser = User::create([
            'name' => 'admin',
            'password' => 'heslo',
            'email' => 'admin@email.com',
            'is_admin' => '1'
        ]);
        $region = Region::create([
            'name' => 'new region'
        ]);

        $response = $this->actingAs($adminUser)->post('/AP/updateRegionName', [ 'id' => $region->id, 'name' => 'updated region']);

        $this->assertDatabaseHas('regions',[ 'name' => 'updated region']);
    }
}
