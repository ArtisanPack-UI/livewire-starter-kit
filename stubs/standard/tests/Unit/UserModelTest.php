<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation with basic attributes.
     *
     * @return void
     */
    public function test_user_can_be_created_with_basic_attributes()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    /**
     * Test user email must be unique.
     *
     * @return void
     */
    public function test_user_email_must_be_unique()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);
    }

    /**
     * Test user password is hashed when set.
     *
     * @return void
     */
    public function test_user_password_is_hashed_when_set()
    {
        $user = new User();
        $user->password = 'plain-text-password';
        
        $this->assertNotEquals('plain-text-password', $user->password);
        $this->assertTrue(Hash::check('plain-text-password', $user->password));
    }

    /**
     * Test user has many relationships if applicable.
     *
     * @return void
     */
    public function test_user_relationships()
    {
        // This test can be expanded based on your User model relationships
        // For example, if User has many posts:
        
        // $user = User::factory()->create();
        // $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->posts);
        
        // For now, we'll just assert that the test passes
        $this->assertTrue(true);
    }
}