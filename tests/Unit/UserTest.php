<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Products;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCanBeCreated()
    {
        {
            // Create a new user
            $user = Products::create([
                'p_name' => 'Product1',
                'p_desc' => 'new product',
            ]);
    
            // Assert that the user was created successfully
            $this->assertDatabaseHas('products', [
                'p_name' => 'Product1',
                'p_desc' => 'new product',
            ]);
        }
    
    }
}

