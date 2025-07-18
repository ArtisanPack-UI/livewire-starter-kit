<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ArtisanpackUi\CmsFramework\Models\User;
use ArtisanpackUi\CmsFramework\Models\Role;
use ArtisanpackUi\CmsFramework\Models\Permission;
use ArtisanpackUi\CmsFramework\Models\Content;

class CmsFrameworkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test CMS User model with extended fields.
     *
     * @return void
     */
    public function test_cms_user_model_with_extended_fields()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('Test', $user->first_name);
        $this->assertEquals('Last', $user->last_name);
        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test role assignment and checking.
     *
     * @return void
     */
    public function test_role_assignment_and_checking()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        
        // Create user
        $user = User::factory()->create();
        
        // Assign role
        $user->assignRole($adminRole);
        
        // Check role
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('editor'));
        
        // Assign another role
        $user->assignRole($editorRole);
        
        // Check multiple roles
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('editor'));
        $this->assertTrue($user->hasAnyRole(['admin', 'editor']));
        $this->assertTrue($user->hasAllRoles(['admin', 'editor']));
        
        // Remove role
        $user->removeRole($adminRole);
        
        // Check after removal
        $this->assertFalse($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('editor'));
    }

    /**
     * Test permission assignment and checking.
     *
     * @return void
     */
    public function test_permission_assignment_and_checking()
    {
        // Create permissions
        $createPermission = Permission::create(['name' => 'create_content', 'display_name' => 'Create Content']);
        $editPermission = Permission::create(['name' => 'edit_content', 'display_name' => 'Edit Content']);
        
        // Create user
        $user = User::factory()->create();
        
        // Assign permission
        $user->assignPermission($createPermission);
        
        // Check permission
        $this->assertTrue($user->hasPermission('create_content'));
        $this->assertFalse($user->hasPermission('edit_content'));
        
        // Assign another permission
        $user->assignPermission($editPermission);
        
        // Check multiple permissions
        $this->assertTrue($user->hasPermission('create_content'));
        $this->assertTrue($user->hasPermission('edit_content'));
        $this->assertTrue($user->hasAnyPermission(['create_content', 'edit_content']));
        $this->assertTrue($user->hasAllPermissions(['create_content', 'edit_content']));
        
        // Remove permission
        $user->removePermission($createPermission);
        
        // Check after removal
        $this->assertFalse($user->hasPermission('create_content'));
        $this->assertTrue($user->hasPermission('edit_content'));
    }

    /**
     * Test role-based permissions.
     *
     * @return void
     */
    public function test_role_based_permissions()
    {
        // Create role and permissions
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        $createPermission = Permission::create(['name' => 'create_content', 'display_name' => 'Create Content']);
        $editPermission = Permission::create(['name' => 'edit_content', 'display_name' => 'Edit Content']);
        
        // Assign permissions to role
        $editorRole->assignPermission($createPermission);
        $editorRole->assignPermission($editPermission);
        
        // Create user and assign role
        $user = User::factory()->create();
        $user->assignRole($editorRole);
        
        // Check permissions through role
        $this->assertTrue($user->hasPermission('create_content'));
        $this->assertTrue($user->hasPermission('edit_content'));
        
        // Remove permission from role
        $editorRole->removePermission($createPermission);
        
        // Refresh user model to get updated permissions
        $user = $user->fresh();
        
        // Check permissions after removal
        $this->assertFalse($user->hasPermission('create_content'));
        $this->assertTrue($user->hasPermission('edit_content'));
    }

    /**
     * Test content creation and management.
     *
     * @return void
     */
    public function test_content_creation_and_management()
    {
        // Create user
        $user = User::factory()->create();
        
        // Create content
        $content = Content::create([
            'type' => 'page',
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => '<p>This is a test page.</p>',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);
        
        // Check content
        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals('page', $content->type);
        $this->assertEquals('Test Page', $content->title);
        $this->assertEquals('test-page', $content->slug);
        $this->assertEquals('<p>This is a test page.</p>', $content->content);
        $this->assertEquals('draft', $content->status);
        $this->assertEquals($user->id, $content->author_id);
        
        // Update content
        $content->update([
            'title' => 'Updated Page',
            'content' => '<p>This is an updated page.</p>',
            'status' => 'published',
        ]);
        
        // Check updated content
        $content = $content->fresh();
        $this->assertEquals('Updated Page', $content->title);
        $this->assertEquals('<p>This is an updated page.</p>', $content->content);
        $this->assertEquals('published', $content->status);
        
        // Delete content
        $content->delete();
        
        // Check content is deleted
        $this->assertNull(Content::find($content->id));
    }

    /**
     * Test content querying by type and status.
     *
     * @return void
     */
    public function test_content_querying_by_type_and_status()
    {
        // Create user
        $user = User::factory()->create();
        
        // Create multiple content items
        Content::create([
            'type' => 'page',
            'title' => 'Page 1',
            'slug' => 'page-1',
            'content' => '<p>Page 1 content.</p>',
            'status' => 'published',
            'author_id' => $user->id,
        ]);
        
        Content::create([
            'type' => 'page',
            'title' => 'Page 2',
            'slug' => 'page-2',
            'content' => '<p>Page 2 content.</p>',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);
        
        Content::create([
            'type' => 'article',
            'title' => 'Article 1',
            'slug' => 'article-1',
            'content' => '<p>Article 1 content.</p>',
            'status' => 'published',
            'author_id' => $user->id,
        ]);
        
        // Query by type
        $pages = Content::whereType('page')->get();
        $this->assertCount(2, $pages);
        
        // Query by status
        $published = Content::whereStatus('published')->get();
        $this->assertCount(2, $published);
        
        // Query by type and status
        $publishedPages = Content::whereType('page')->whereStatus('published')->get();
        $this->assertCount(1, $publishedPages);
        $this->assertEquals('Page 1', $publishedPages->first()->title);
    }

    /**
     * Test role-based access component.
     *
     * @return void
     */
    public function test_role_based_access_component()
    {
        // Create roles and permissions
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        $createPermission = Permission::create(['name' => 'create_content', 'display_name' => 'Create Content']);
        
        // Create users with different roles
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);
        
        $editorUser = User::factory()->create();
        $editorUser->assignRole($editorRole);
        $editorUser->assignPermission($createPermission);
        
        $regularUser = User::factory()->create();
        
        // Test role-based access for admin
        $this->actingAs($adminUser);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        
        // Test role-based access for editor
        $this->actingAs($editorUser);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
        
        // Test permission-based access for editor
        $response = $this->get('/admin/content/create');
        $response->assertStatus(200);
        
        // Test role-based access for regular user
        $this->actingAs($regularUser);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
        
        $response = $this->get('/admin/content/create');
        $response->assertStatus(403);
    }
}