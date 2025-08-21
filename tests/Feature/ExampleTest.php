<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Bid;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Market;
use App\Models\Profile;
use App\Models\CartItem;
use App\Models\Chemical;
use App\Models\Inventory;
use App\Models\SerialNumber;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\RegisterController;

class RedirectToTestController extends \App\Http\Controllers\Auth\LoginController {
    public function publicRedirectTo() {
        return $this->redirectTo();
    }
}


class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_faculty_redirect()
    {
        $user = User::factory()->create(['role' => 'faculty']);
        $this->actingAs($user);

        $controller = new RedirectToTestController();
        $this->assertEquals('/inventory', $controller->publicRedirectTo());
    }

    public function test_supplier_redirect()
    {
        $user = User::factory()->create(['role' => 'supplier']);
        $this->actingAs($user);

        $controller = new RedirectToTestController();
        $this->assertEquals('/market', $controller->publicRedirectTo());
    }

    public function test_admin_redirect()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $controller = new RedirectToTestController();
        $this->assertEquals('/inventory', $controller->publicRedirectTo());
    }

    public function test_unknown_role_redirect()
    {
        $user = User::factory()->create(['role' => 'student']);
        $this->actingAs($user);

        $controller = new RedirectToTestController();
        $this->assertEquals('/', $controller->publicRedirectTo());
    }

    public function test_logout_while_logged_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_logout_while_not_logged_in()
    {
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_faculty_email_with_valid_domain()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@student.usm.my',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'FACULTY',
        ];

        $controller = new RegisterController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('validator');
        $method->setAccessible(true);
        $validator = $method->invoke($controller, $data);


        $this->assertFalse($validator->fails());
    }

    /** WB-11 */
    public function test_faculty_email_with_invalid_domain()
    {
        $data = [
            'name' => 'Wrong Domain',
            'email' => 'wrong@ymail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'FACULTY',
        ];

        $controller = new RegisterController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('validator');
        $method->setAccessible(true);
        $validator = $method->invoke($controller, $data);


        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** WB-12 */
    public function test_supplier_email_validation_passes()
    {
        $data = [
            'name' => 'Kira',
            'email' => 'kira@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'SUPPLIER',
        ];

        $controller = new RegisterController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('validator');
        $method->setAccessible(true);
        $validator = $method->invoke($controller, $data);


        $this->assertFalse($validator->fails());
    }

    // /** WB-13 */
    // public function test_create_user_and_profile()
    // {
    //     $data = [
    //         'name' => 'Ahmad',
    //         'email' => 'ahmad@gmail.com',
    //         'password' => 'ahmad123',
    //         'password_confirmation' => 'ahmad123',
    //         'role' => 'ADMIN',
    //         'phone_number' => '0139998888',
    //     ];

    //     $controller = new RegisterController();
    //     $user = $controller->create($data);

    //     $this->assertDatabaseHas('users', [
    //         'email' => 'ahmad@gmail.com',
    //         'role' => 'ADMIN',
    //     ]);

    //     $this->assertDatabaseHas('profiles', [
    //         'user_id' => $user->id,
    //         'phone_number' => '0139998888',
    //     ]);
    // }

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Create a user with permission
    //     $this->user = User::factory()->create(['role' => 'FACULTY']);
    //     $this->actingAs($this->user);
    // }

    // public function test_empty_search_returns_paginated_chemicals()
    // {
    //     Chemical::factory()->count(15)->create();

    //     $response = $this->get('/inventory?search=');

    //     $response->assertStatus(200);
    //     $response->assertViewHas('chemicals');
    //     $this->assertCount(10, $response->viewData('chemicals'));
    // }

    // /** WB-15: Search by Chemical Name or Attributes */
    // public function test_search_matches_chemical_attributes()
    // {
    //     Chemical::factory()->create(['chemical_name' => 'Sulfuric Acid']);
    //     Chemical::factory()->create(['CAS_number' => 'Sulfuric-123']);
    //     Chemical::factory()->create(['ec_number' => 'XYZ-Sulfuric']);

    //     $response = $this->get('/inventory?search=sulfuric');

    //     $response->assertStatus(200);
    //     $response->assertViewHas('chemicals');

    //     $chemicals = $response->viewData('chemicals');
    //     $this->assertGreaterThanOrEqual(1, $chemicals->count());
    // }

    // /** WB-16: Next Page Button Click */
    // public function test_next_page_shows_second_batch()
    // {
    //     Chemical::factory()->count(25)->create();

    //     $response = $this->get('/inventory?page=2');

    //     $response->assertStatus(200);
    //     $response->assertViewHas('chemicals');
    //     $chemicals = $response->viewData('chemicals');

    //     $this->assertCount(10, $chemicals);
    //     $this->assertEquals(2, $chemicals->currentPage());
    // }

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     Storage::fake('public');
    //     $this->user = User::factory()->create(['role' => 'ADMIN']);
    //     $this->actingAs($this->user);
    // }

    // /** WB-17 */
    // public function test_new_chemical_is_stored()
    // {
    //     $response = $this->post('/i/chemical', [
    //         'chemical_name' => 'sulfur acid',
    //         'CAS_number' => '345-6778',
    //         'empirical_formula' => 'H2O9',
    //         'chemical_structure' => UploadedFile::fake()->image('structure.png'),
    //         'SDS_file' => UploadedFile::fake()->create('SDS_file.pdf', 100, 'application/pdf'),
    //     ]);

    //     $response->assertRedirect('/');
    //     $this->assertDatabaseHas('chemicals', [
    //         'chemical_name' => 'sulfur acid',
    //         'CAS_number' => '345-6778',
    //     ]);
    // }

    // /** WB-18 */
    // public function test_duplicate_chemical_returns_error()
    // {
    //     Chemical::factory()->create([
    //         'user_id' => 1,
    //         'chemical_name' => 'sulfur acid',
    //         'CAS_number' => '345-6778',
    //         'empirical_formula' => 'H2O9',
    //         'chemical_structure' => 'uploads/fake.png',
    //         'SDS_file' => 'uploads/fake.pdf',
    //     ]);

    //     $response = $this->from('/createChemical')->post('/i/chemical', [
    //         'chemical_name' => 'sulfur acid',
    //         'CAS_number' => '345-6778',
    //         'empirical_formula' => 'H2O9',
    //         // 'chemical_structure' => UploadedFile::fake()->image('structure.png'),
    //         // 'SDS_file' => UploadedFile::fake()->create('SDS_file.pdf', 100, 'application/pdf'),
    //     ]);

    //     $response->assertRedirect('/createChemical');
    //     // $response->assertSessionHas('success', 'Chemical already registered');
    //     $response->assertSessionHasErrors(['CAS_number']);
    // }

    // /** WB-19 */
    // public function test_missing_required_chemical_name_returns_validation_error()
    // {
    //     $response = $this->from('/createChemicals')->post('/i/chemical', [
    //         'chemical_name' => '',
    //         'CAS_number' => '345-6778',
    //         'empirical_formula' => 'H2O9',
    //         // 'chemical_structure' => UploadedFile::fake()->image('structure.png'),
    //         // 'SDS_file' => UploadedFile::fake()->create('SDS_file.pdf', 100, 'application/pdf'),
    //     ]);

    //     $response->assertRedirect('/createChemicals');
    //     $response->assertSessionHasErrors(['chemical_name']);
    // }

    // protected $user;
    // protected $chemical;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     Storage::fake('public');

    //     $this->user = User::factory()->create(['role' => 'ADMIN']);
    //     $this->actingAs($this->user);

    //     // $this->chemical = Chemical::factory()->create([
    //     //     'chemical_name' => 'Old Name',
    //     //     'CAS_number' => '345-6778',
    //     //     'empirical_formula' => 'H2O',
    //     //     'chemical_structure' => 'chemical_structure/old.png',
    //     //     'SDS_file' => 'SDS_file/old.pdf',
    //     //     'reg_by' => $this->user->name,
    //     // ]);
    // }

    // /** WB-20 */
    // public function test_user_updates_chemical_with_new_info()
    // {
    //     $response = $this->patch("/i/c/1", [
    //         'chemical_name' => 'hydrogen',
    //         'CAS_number' => '345-6779',
    //         'empirical_formula' => 'H2O1',
    //         // 'chemical_structure' => UploadedFile::fake()->image('new_structure.png'),
    //         // 'SDS_file' => UploadedFile::fake()->create('new_sds.pdf', 100, 'application/pdf'),
    //     ]);

    //     $response->assertRedirect('/i/1');
    //     $this->assertDatabaseHas('chemicals', [
    //         'id' => 1,
    //         'chemical_name' => 'hydrogen',
    //         'empirical_formula' => 'H2O1',
    //     ]);
    // }

    // /** WB-21 */
    // public function test_user_updates_chemical_with_same_data()
    // {
    //     $response = $this->patch("/i/c/1", [
    //         'chemical_name' => 'hydrogen',
    //         'CAS_number' => '345-6779',
    //         'empirical_formula' => 'H2O1',
    //     ]);

    //     $response->assertRedirect('/i/1');

    //     $this->assertDatabaseHas('chemicals', [
    //         'id' => 1,
    //         'chemical_name' => 'hydrogen',
    //         'CAS_number' => '345-6779',
    //         'empirical_formula' => 'H2O1',
    //     ]);
    // }

    // protected $user;
    // protected $chemical;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     $this->user = User::factory()->create(['role' => 'ADMIN']);
    //     $this->actingAs($this->user);

    //     // $this->chemical = Chemical::factory()->create([
    //     //     'chemical_name' => 'Sulfuric Acid',
    //     //     'CAS_number' => '123-45-6',
    //     // ]);
    // }

    // /** WB-22 */
    // public function test_user_can_add_new_chemical_containers()
    // {
    //     $response = $this->post("/i/inventory/1", [
    //         'chemical_id' => 1,
    //         'serial_number' => 'LB-001',
    //         'notes' => "for dr Aish’ class",
    //         'location' => 'Lab A',
    //         'packaging_type' => 'bottle',
    //         'quantity' => 250,
    //         'unit' => 'ml',
    //         'acq_at' => now()->format('Y-m-d'),
    //         'exp_at' => now()->addYear()->format('Y-m-d'),
    //         'container_count' => 30,
    //         'min_quantity' => 500,
    //         'brand' => 'Merck',
    //     ]);

    //     $response->assertRedirect();

    //     $this->assertDatabaseCount('inventories', 30);
    //     $this->assertDatabaseHas('inventories', [
    //         'chemical_id' => 1,
    //         'serial_number' => 'LB-001',
    //         'location' => 'Lab A',
    //         'packaging_type' => 'bottle',
    //     ]);
    // }

    // /** WB-23 */
    // public function test_duplicate_serial_number_gives_error()
    // {
    //     // Inventory::factory()->create([
    //     //     'serial_number' => 'LB-001',
    //     //     'chemical_id' => $this->chemical->id,
    //     //     'user_id' => $this->user->id,
    //     // ]);

    //     $response = $this->from("/i/createInventory/1")->post("/i/inventory/1", [
    //         'chemical_id' => 1,
    //         'serial_number' => 'LB-001',
    //         'notes' => "another use",
    //         'location' => 'Lab A',
    //         'packaging_type' => 'bottle',
    //         'quantity' => 250,
    //         'unit' => 'ml',
    //         'acq_at' => now()->format('Y-m-d'),
    //         'exp_at' => now()->addYear()->format('Y-m-d'),
    //         'container_count' => 1,
    //         'min_quantity' => 500,
    //     ]);

    //     $response->assertRedirect("/i/createInventory/1");
    //     $response->assertSessionHasErrors('serial_number');
    // }

    // protected $user;
    // protected $chemical;
    // protected $inventory;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     $this->user = User::factory()->create(['role' => 'ADMIN']);
    //     $this->actingAs($this->user);

    //     // $this->chemical = Chemical::factory()->create();
    //     // $this->inventory = Inventory::factory()->create([
    //     //     'chemical_id' => $this->chemical->id,
    //     //     'user_id' => $this->user->id,
    //     // ]);
    // }

    // /** WB-24 */
    // public function test_user_can_delete_existing_inventory()
    // {
    //     // $this->chemical = Chemical::factory()->create();
    //     $this->inventory = Inventory::factory()->create([
    //         'chemical_id' => 1,
    //         'user_id' => 1,
    //         'status' => 'empty',
    //     ]);
    //     $response = $this->delete("/i/{$this->inventory->id}/delete");

    //     $response->assertRedirect('/');
    //     $this->assertDatabaseMissing('inventories', [
    //         'id' => $this->inventory->id,
    //     ]);
    // }

    // /** WB-25 */
    // public function test_deleting_non_existent_inventory_shows_404()
    // {
    //     // $invalidId = $this->inventory->id + 999;

    //     $response = $this->delete("/inventory/999/delete");

    //     $response->assertNotFound();
    // }

    // protected $admin;
    // protected $supplier;
    // protected $chemical;
    // protected $inventory;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     $this->admin = User::factory()->create(['role' => 'ADMIN']);
    //     $this->supplier = User::factory()->create(['role' => 'SUPPLIER']);

    //     // $this->chemical = Chemical::factory()->create();

    //     // $this->inventory = Inventory::factory()->create([
    //     //     'chemical_id' => $this->chemical->id,
    //     //     'user_id' => $this->admin->id,
    //     //     'status' => 'sealed',
    //     // ]);
    // }

    // /** WB-26 */
    // public function test_authorized_user_can_unseal_inventory()
    // {
    //     $this->chemical = Chemical::factory()->create([
    //         'user_id' => $this->admin->id,
    //     ]);

    //     $this->inventory = Inventory::factory()->create([
    //         'chemical_id' => $this->chemical->id,
    //         'user_id' => $this->admin->id,
    //         'status' => 'sealed',
    //     ]);

    //     $this->actingAs($this->admin);

    //     $response = $this->get("/i/{$this->inventory->id}/unseal");

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('inventories', [
    //         'id' => $this->inventory->id,
    //         'status' => 'opened',
    //     ]);
    // }

    // /** WB-27 */
    // public function test_unauthorized_user_cannot_unseal_inventory()
    // {
    //     $this->chemical = Chemical::factory()->create([
    //         'user_id' => $this->admin->id,
    //     ]);

    //     $this->inventory = Inventory::factory()->create([
    //         'chemical_id' => $this->chemical->id,
    //         'user_id' => $this->admin->id,
    //         'status' => 'sealed',
    //     ]);
    //     $this->actingAs($this->supplier);

    //     $response = $this->get("/i/{$this->inventory->id}/unseal");

    //     $response->assertForbidden();
    //     $this->assertDatabaseHas('inventories', [
    //         'id' => $this->inventory->id,
    //         'status' => 'sealed',
    //     ]);
    // }

    // protected $user;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->user = User::factory()->create(['role' => 'FACULTY']);
    //     $this->actingAs($this->user);
    // }

    // /** WB-28 */
    // public function test_reduce_quantity_and_log_usage()
    // {
    //     $chemical = Chemical::factory()->create([
    //         'user_id' => $this->user->id,
    //     ]);
    //     $inventory = Inventory::factory()->create([
    //         'chemical_id' => $chemical->id,
    //         'user_id' => $this->user->id,
    //         'quantity' => 250,
    //         'serial_number' => 'A-123',
    //         'min_quantity' => 50
    //     ]);

    //     // SerialNumber::factory()->create(['serial_number' => 'A-123']);

    //     $response = $this->post("/i/{$inventory->id}/reduce", [
    //         'quantity_used' => 100,
    //         'reason' => 'for Prof. Aisyh lab'
    //     ]);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('inventories', [
    //         'id' => $inventory->id,
    //         'quantity' => 150,
    //     ]);
    //     $this->assertDatabaseHas('inventory_usages', [
    //         'inventory_id' => $inventory->id,
    //         'quantity_used' => 100,
    //         'reason' => 'for Prof. Aisyh lab',
    //     ]);
    // }

    // /** WB-29 */
    // public function test_reduce_quantity_to_zero_triggers_empty_alert()
    // {
    //     $chemical = Chemical::factory()->create([
    //         'user_id' => $this->user->id,
    //     ]);
    //     $inventory = Inventory::factory()->create([
    //         'chemical_id' => $chemical->id,
    //         'user_id' => $this->user->id,
    //         'quantity' => 100,
    //         'serial_number' => 'B-321',
    //         'min_quantity' => 50
    //     ]);

    //     // SerialNumber::factory()->create(['serial_number' => 'B-321']);

    //     $response = $this->post("/i/{$inventory->id}/reduce", [
    //         'quantity_used' => 100,
    //         'reason' => 'for Prof. Aisyh lab'
    //     ]);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('inventories', [
    //         'id' => $inventory->id,
    //         'quantity' => 0,
    //         'status' => 'empty',
    //     ]);
    //     $this->assertDatabaseHas('alerts', [
    //         'message' => "Container for one of {$inventory->chemical->chemical_name} ({$inventory->serial_number} #{$inventory->container_number}) is depleted.
    //                             Please handle the empty container.",
    //     ]);
    // }

    // /** WB-30 */
    // public function test_reduce_quantity_triggers_threshold_alert()
    // {
    //     $chemical = Chemical::factory()->create([
    //         'user_id' => $this->user->id,
    //     ]);
    //     $inventory = Inventory::factory()->create([
    //         'chemical_id' => $chemical->id,
    //         'user_id' => $this->user->id,
    //         'quantity' => 100,
    //         'serial_number' => 'C-456',
    //         'min_quantity' => 4000
    //     ]);

    //     $response = $this->post("/i/{$inventory->id}/reduce", [
    //         'quantity_used' => 50,
    //         'reason' => 'for Prof. Aisyh lab'
    //     ]);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('inventories', [
    //         'id' => $inventory->id,
    //         'quantity' => 50,
    //     ]);
    //     $this->assertDatabaseHas('alerts', [
    //         'message' => "Warning: Threshold reached for {$inventory->chemical->chemical_name} ({$inventory->serial_number}).
    //                             Please restock as soon as possible.",
    //     ]);
    // }

    //

    // /** @test */
    // public function test_user_can_update_profile_with_valid_data()
    // {
    //     Storage::fake('public');

    //     $user = User::factory()->create();
    //     $profile = Profile::factory()->create(['user_id' => $user->id]);

    //     $this->actingAs($user);

    //     $response = $this->patch("/profile/{$user->id}", [
    //         'user_name' => 'New Name',
    //         'email' => 'new@email.com',
    //         'status' => 'Active',
    //         'score' => '88',
    //         'phone_number' => '0123456789',
    //         'address' => 'no8, Jln Ketaping, Gelugor',
    //         'city' => 'Penang',
    //         'postal' => '11700',
    //     ]);

    //     $response->assertRedirect("/profile/{$user->id}");

    //     $this->assertDatabaseHas('users', [
    //         'id' => $user->id,
    //         'name' => 'New Name',
    //         'email' => 'new@email.com',
    //     ]);

    //     $this->assertDatabaseHas('profiles', [
    //         'user_id' => $user->id,
    //         'city' => 'Penang',
    //         'postal' => '11700',
    //         'address' => 'no8, Jln Ketaping, Gelugor',
    //     ]);
    // }

    // /** @test */
    // public function test_user_update_without_changes_retains_data()
    // {
    //     $user = User::factory()->create([
    //         'name' => 'Original Name',
    //         'email' => 'original@email.com',
    //     ]);

    //     $profile = Profile::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => 'Active',
    //         'score' => '90',
    //         'city' => 'Penang',
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->patch("/profile/{$user->id}", [
    //         'user_name' => 'Original Name',
    //         'email' => 'original@email.com',
    //         'status' => 'Active',
    //         'score' => '90',
    //         'phone_number' => '',
    //         'address' => '',
    //         'city' => 'Penang',
    //         'postal' => '',
    //     ]);

    //     $response->assertRedirect("/profile/{$user->id}");

    //     $this->assertDatabaseHas('profiles', [
    //         'user_id' => $user->id,
    //         'city' => 'Penang',
    //     ]);
    // }

    // /** @test */
    // public function test_admin_can_create_market_request()
    // {
    //     Notification::fake();

    //     $admin = User::factory()->create(['role' => 'ADMIN']);
    //     $chemical = Chemical::factory()->create([
    //         'user_id' => $admin->id,
    //     ]);
    //     $inventory = Inventory::factory()->create(['chemical_id' => $chemical->id]);
    //     $supplier = User::factory()->create(['role' => 'SUPPLIER']);

    //     $this->actingAs($admin);

    //     $response = $this->post('/m/store', [
    //         'inventory_id' => $inventory->id,
    //         'chemical_id' => $chemical->id,
    //         'stock_needed' => 20,
    //         'quantity_needed' => 250,
    //         'packaging_type' => 'bottle',
    //         'unit' => 'ml',
    //         'notes' => 'urgent',
    //     ]);

    //     $response->assertRedirect('/market');
    //     $this->assertDatabaseHas('markets', [
    //         'chemical_id' => $chemical->id,
    //         'stock_needed' => 20,
    //     ]);

    //     Notification::assertSentTo([$supplier], \App\Notifications\NewMarket::class);
    // }

    // /** @test */
    // public function test_market_request_fails_for_nonexistent_chemical()
    // {
    //     $admin = User::factory()->create(['role' => 'ADMIN']);

    //     $this->actingAs($admin);

    //     $response = $this->post('/m/store', [
    //         'inventory_id' => 999,
    //         'chemical_id' => 20,
    //         'stock_needed' => 20,
    //         'quantity_needed' => 250,
    //         'packaging_type' => 'bottle',
    //         'unit' => 'ml',
    //         'notes' => 'urgent',
    //     ]);

    //     $response->assertSessionHasErrors('inventory_id');
    // }

    // /** @test */
    // public function test_market_request_requires_all_fields()
    // {
    //     $admin = User::factory()->create(['role' => 'ADMIN']);
    //     $this->actingAs($admin);

    //     $response = $this->post('/m/store', []);

    //     $response->assertSessionHasErrors(['inventory_id', 'chemical_id', 'quantity_needed', 'stock_needed', 'unit', 'packaging_type']);
    // }

    // /** @test */
    // public function supplier_can_submit_bid_with_tiers()
    // {
    //     $supplier = User::factory()->create(['role' => 'SUPPLIER']);
    //     $chemical = Chemical::factory()->create([
    //         'user_id' => 1,
    //     ]);
    //     $inventory = Inventory::factory()->create(['chemical_id' => $chemical->id]);
    //     $market = Market::factory()->create([
    //         'chemical_id' => $chemical->id,
    //         'inventory_id' => $inventory->id,
    //         'user_id' => $supplier->id,
    //     ]);

    //     $this->actingAs($supplier);

    //     $response = $this->post("/m/{$market->id}/bid", [
    //         'price' => 400,
    //         'quantity' => 250,
    //         'stock' => 500,
    //         'notes' => 'Urgent delivery',
    //         'tiers' => [
    //             ['tier' => 'Tier 1', 'min_qty' => 100, 'price' => 200],
    //             ['tier' => 'Tier 2', 'min_qty' => 200, 'price' => 150],
    //         ]
    //     ]);

    //     $response->assertRedirect(route('market.detail', $market->id));
    //     $this->assertDatabaseHas('bids', ['market_id' => $market->id, 'price' => 400]);
    //     $this->assertDatabaseHas('bulk_prices', ['tier' => 'Tier 1', 'price_per_unit' => 200]);
    //     $this->assertDatabaseHas('bulk_prices', ['tier' => 'Tier 2', 'price_per_unit' => 150]);
    // }

    // /** @test */
    // public function bid_fails_if_market_not_found()
    // {
    //     $supplier = User::factory()->create(['role' => 'supplier']);
    //     $this->actingAs($supplier);

    //     $invalidMarketId = 9999;

    //     $response = $this->post("/market/{$invalidMarketId}/bid", [
    //         'price' => 400,
    //         'quantity' => 250,
    //         'stock' => 500,
    //         'notes' => 'Invalid request',
    //         'tiers' => [
    //             ['tier' => 'Tier 1', 'min_qty' => 100, 'price' => 200],
    //         ]
    //     ]);

    //     $response->assertNotFound();
    // }

    // /** @test */
    // public function admin_can_add_bid_to_cart_with_valid_quantity()
    // {
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $supplier = User::factory()->create(['role' => 'supplier']);

    //     $bid = Bid::factory()->create([
    //         'user_id' => $supplier->id,
    //         'stock' => 100,
    //         'market_id' => 1,
    //     ]);

    //     $this->actingAs($admin);

    //     $response = $this->post("/cart/add/{$bid->id}", [
    //         'quantity' => 20,
    //     ]);

    //     $response->assertRedirect(route('cart.index'));
    //     $this->assertDatabaseHas('carts', [
    //         'user_id' => $admin->id,
    //         'supplier_id' => $supplier->id,
    //         'status' => 'Pending',
    //     ]);
    //     $this->assertDatabaseHas('cart_items', [
    //         'bid_id' => $bid->id,
    //         'quantity' => 20,
    //     ]);
    // }

    // /** @test */
    // public function cannot_add_quantity_more_than_stock()
    // {
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $supplier = User::factory()->create(['role' => 'supplier']);

    //     $bid = Bid::factory()->create([
    //         'user_id' => $supplier->id,
    //         'stock' => 50,
    //         'market_id' => 1,
    //     ]);

    //     $this->actingAs($admin);

    //     $response = $this->post("/cart/add/{$bid->id}", [
    //         'quantity' => 800,
    //     ]);

    //     $response->assertSessionHasErrors();
    //     $this->assertDatabaseMissing('cart_items', [
    //         'bid_id' => $bid->id,
    //     ]);
    // }

    //  /** @test */
    // public function admin_can_increase_cart_item_quantity_if_stock_allows()
    // {
    //     $admin = User::factory()->create(['role' => 'ADMIN']);
    //     $bid = Bid::factory()->create(['stock' => 10, 'market_id' => 1]);

    //     $cart = Cart::factory()->create([
    //         'user_id' => $admin->id,
    //         'supplier_id' => $bid->user_id,
    //         'status' => 'Pending',
    //     ]);

    //     $item = CartItem::factory()->create([
    //         'cart_id' => $cart->id,
    //         'bid_id' => $bid->id,
    //         'quantity' => 5,
    //     ]);

    //     $this->actingAs($admin);

    //     $response = $this->patch("/cart/update/{$item->id}", [
    //         'action' => 'increase',
    //     ]);

    //     $response->assertSessionHas('success');
    //     $this->assertDatabaseHas('cart_items', [
    //         'id' => $item->id,
    //         'quantity' => 6,
    //     ]);
    // }

    /** @test */
    public function admin_can_decrease_cart_item_quantity_and_delete_if_zero()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $bid = Bid::factory()->create(['stock' => 10, 'market_id' => 1]);

        $cart = Cart::factory()->create([
            'user_id' => $admin->id,
            'supplier_id' => $bid->user_id,
            'status' => 'Pending',
        ]);

        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'bid_id' => $bid->id,
            'quantity' => 1,
        ]);

        $this->actingAs($admin);

        $response = $this->patch("/cart/update/{$item->id}", [
            'action' => 'decrease',
        ]);

        // $response->assertSessionHas('success');
        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);
    }
}
