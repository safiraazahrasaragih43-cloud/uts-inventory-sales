<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test authenticated staff/admin can view customers list.
     */
    public function test_authenticated_user_can_view_customers_list(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $response = $this->actingAs($staff)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Pelanggan BlushBoutique');
        $response->assertSee('Clarissa Stephanie');
    }

    /**
     * Test authenticated user can view customer details, profile, and purchase history.
     */
    public function test_authenticated_user_can_view_customer_details(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $customer = Customer::first();

        $response = $this->actingAs($staff)->get(route('customers.show', $customer));

        $response->assertStatus(200);
        $response->assertSee($customer->name);
        $response->assertSee('Detail & Riwayat Belanja Pelanggan');
        $response->assertSee('Total Akumulasi Belanja');
        $response->assertSee('Riwayat Transaksi Belanja');
    }

    /**
     * Test create new customer with validation.
     */
    public function test_can_create_new_customer(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();

        // 1. Validation error if name is empty
        $responseEmpty = $this->actingAs($staff)->post(route('customers.store'), [
            'name' => '',
            'phone' => '081122334455',
        ]);
        $responseEmpty->assertSessionHasErrors('name');

        // 2. Successful creation
        $response = $this->actingAs($staff)->post(route('customers.store'), [
            'name' => 'Tasya Farasya',
            'phone' => '081299998888',
            'email' => 'tasya@farasya.id',
            'address' => 'Jl. Kemang Raya No. 10, Jakarta Selatan',
        ]);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', [
            'name' => 'Tasya Farasya',
            'phone' => '081299998888',
        ]);
    }

    /**
     * Test edit and update customer.
     */
    public function test_can_update_customer(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $customer = Customer::first();

        $response = $this->actingAs($staff)->put(route('customers.update', $customer), [
            'name' => 'Clarissa Stephanie Updated',
            'phone' => '081200001111',
            'email' => 'clarissa.new@gmail.com',
            'address' => 'Jl. Ir. H. Juanda No. 99, Bandung',
        ]);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Clarissa Stephanie Updated',
            'phone' => '081200001111',
        ]);
    }

    /**
     * Test delete customer.
     */
    public function test_can_delete_customer(): void
    {
        $admin = User::where('email', 'admin@blushboutique.com')->first();
        $customer = Customer::create([
            'name' => 'Customer To Delete',
            'phone' => '089999999999',
        ]);

        $response = $this->actingAs($admin)->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    /**
     * Test sales transaction with registered customer_id.
     */
    public function test_sale_transaction_with_registered_customer(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $customer = Customer::first();
        $product = Product::first();

        $response = $this->actingAs($staff)->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'payment_method' => 'QRIS',
            'notes' => 'Member transaction test',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $sale = Sale::latest('id')->first();
        $this->assertEquals($customer->id, $sale->customer_id);
        $this->assertEquals($customer->name, $sale->customer_name);
        $this->assertNotNull($sale->customer);
        $this->assertEquals($customer->name, $sale->customer->name);
    }

    /**
     * Test sales transaction with walk-in customer (null customer_id).
     */
    public function test_sale_transaction_with_walk_in_customer(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $product = Product::first();

        $response = $this->actingAs($staff)->post(route('sales.store'), [
            'customer_id' => '',
            'customer_name' => 'Kak Aurel (Walk-in)',
            'payment_method' => 'Tunai',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $sale = Sale::latest('id')->first();
        $this->assertNull($sale->customer_id);
        $this->assertEquals('Kak Aurel (Walk-in)', $sale->customer_name);
        $this->assertNull($sale->customer);
    }
}
