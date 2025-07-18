<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view their profile page.
     *
     * @return void
     */
    public function test_user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    /**
     * Test user can update their profile information.
     *
     * @return void
     */
    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    /**
     * Test user can update their password.
     *
     * @return void
     */
    public function test_user_can_update_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $response = $this->actingAs($user)
            ->patch('/profile/password', [
                'current_password' => 'current-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /**
     * Test password update validation.
     *
     * @return void
     */
    public function test_password_update_validation()
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        // Test with incorrect current password
        $response = $this->actingAs($user)
            ->patch('/profile/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertSessionHasErrors('current_password');

        // Test with password confirmation mismatch
        $response = $this->actingAs($user)
            ->patch('/profile/password', [
                'current_password' => 'current-password',
                'password' => 'new-password',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertSessionHasErrors('password');

        // Test with short password
        $response = $this->actingAs($user)
            ->patch('/profile/password', [
                'current_password' => 'current-password',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test email verification status is displayed on profile page.
     *
     * @return void
     */
    public function test_email_verification_status_is_displayed()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertSee('Your email address is unverified.');

        $user->email_verified_at = now();
        $user->save();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertDontSee('Your email address is unverified.');
    }

    /**
     * Test user can delete their account.
     *
     * @return void
     */
    public function test_user_can_delete_their_account()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull(User::find($user->id));
    }

    /**
     * Test account deletion validation.
     *
     * @return void
     */
    public function test_account_deletion_validation()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Test with incorrect password
        $response = $this->actingAs($user)
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertNotNull(User::find($user->id));
    }
}