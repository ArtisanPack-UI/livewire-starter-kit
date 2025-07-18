<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Tests\TestCase;
use ArtisanpackUi\CmsFramework\Models\User;
use ArtisanpackUi\CmsFramework\Models\Role;
use ArtisanpackUi\CmsFramework\Models\Permission;
use ArtisanpackUi\CmsFramework\Models\Content;

class CmsFrameworkIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Register the CMS Framework components
        $this->registerCmsComponents();
    }

    /**
     * Register CMS Framework components for testing.
     *
     * @return void
     */
    protected function registerCmsComponents()
    {
        // Register the role-based-access component
        View::composer('*', function ($view) {
            $view->with('auth', auth());
        });

        // Register the content-manager component
        if (!View::exists('components.role-based-access')) {
            View::addNamespace('cms-components', base_path('stubs/cms-compatible/components'));
        }
    }

    /**
     * Test that the CMS Framework User model is correctly integrated.
     *
     * @return void
     */
    public function test_cms_framework_user_model_integration()
    {
        // Create a user with the CMS Framework User model
        $user = User::factory()->create([
            'username' => 'testuser',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        // Assert the user was created with the correct attributes
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('Test', $user->first_name);
        $this->assertEquals('User', $user->last_name);
        $this->assertEquals('test@example.com', $user->email);

        // Test authentication with the CMS Framework User model
        $this->actingAs($user);
        $this->assertAuthenticated();
    }

    /**
     * Test that the role-based access component is correctly integrated.
     *
     * @return void
     */
    public function test_role_based_access_component_integration()
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

        // Test role-based access component with admin role
        $this->actingAs($adminUser);
        $html = View::make('cms-components::role-based-access', [
            'role' => 'admin',
            'slot' => 'Admin content',
        ])->render();
        $this->assertStringContainsString('Admin content', $html);

        // Test role-based access component with editor role
        $this->actingAs($editorUser);
        $html = View::make('cms-components::role-based-access', [
            'role' => 'admin',
            'slot' => 'Admin content',
        ])->render();
        $this->assertStringNotContainsString('Admin content', $html);

        $html = View::make('cms-components::role-based-access', [
            'role' => 'editor',
            'slot' => 'Editor content',
        ])->render();
        $this->assertStringContainsString('Editor content', $html);

        // Test role-based access component with permission
        $html = View::make('cms-components::role-based-access', [
            'permission' => 'create_content',
            'slot' => 'Create content permission',
        ])->render();
        $this->assertStringContainsString('Create content permission', $html);

        // Test role-based access component with regular user
        $this->actingAs($regularUser);
        $html = View::make('cms-components::role-based-access', [
            'role' => 'admin',
            'slot' => 'Admin content',
        ])->render();
        $this->assertStringNotContainsString('Admin content', $html);

        $html = View::make('cms-components::role-based-access', [
            'permission' => 'create_content',
            'slot' => 'Create content permission',
        ])->render();
        $this->assertStringNotContainsString('Create content permission', $html);
    }

    /**
     * Test that the content manager component is correctly integrated.
     *
     * @return void
     */
    public function test_content_manager_component_integration()
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a role with content management permissions
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        $createPermission = Permission::create(['name' => 'create_page', 'display_name' => 'Create Page']);
        $editPermission = Permission::create(['name' => 'edit_page', 'display_name' => 'Edit Page']);
        $deletePermission = Permission::create(['name' => 'delete_page', 'display_name' => 'Delete Page']);
        $publishPermission = Permission::create(['name' => 'publish_page', 'display_name' => 'Publish Page']);

        $editorRole->assignPermission($createPermission);
        $editorRole->assignPermission($editPermission);
        $editorRole->assignPermission($deletePermission);
        $editorRole->assignPermission($publishPermission);

        $user->assignRole($editorRole);

        // Create content type configuration
        config(['cms-framework.content_types.page' => [
            'name' => 'Page',
            'name_plural' => 'Pages',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'required' => true,
                ],
                'content' => [
                    'type' => 'wysiwyg',
                    'required' => true,
                ],
            ],
            'statuses' => ['draft', 'published', 'archived'],
            'default_status' => 'draft',
        ]]);

        // Create a content item
        $content = Content::create([
            'type' => 'page',
            'title' => 'Test Page',
            'content' => '<p>This is a test page.</p>',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);

        // Test content manager component with content item
        $html = View::make('cms-components::content-manager', [
            'contentType' => 'page',
            'contentId' => $content->id,
            'showTitle' => true,
            'showStatus' => true,
            'showActions' => true,
            'allowEdit' => true,
            'allowDelete' => true,
            'allowPublish' => true,
            'slot' => '',
        ])->render();

        // Assert the component contains the content title
        $this->assertStringContainsString('Test Page', $html);
        
        // Assert the component contains the content status
        $this->assertStringContainsString('draft', $html);
        
        // Assert the component contains edit, publish, and delete buttons
        $this->assertStringContainsString('Edit', $html);
        $this->assertStringContainsString('Publish', $html);
        $this->assertStringContainsString('Delete', $html);
    }

    /**
     * Test that the CMS Framework views are correctly integrated.
     *
     * @return void
     */
    public function test_cms_framework_views_integration()
    {
        // Create a user
        $user = User::factory()->create([
            'username' => 'testuser',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        // Test register view
        $registerView = View::file(base_path('stubs/cms-compatible/register.blade.php'))->render();
        $this->assertStringContainsString('username', $registerView);
        $this->assertStringContainsString('first_name', $registerView);
        $this->assertStringContainsString('last_name', $registerView);

        // Test profile view
        $this->actingAs($user);
        $profileView = View::file(base_path('stubs/cms-compatible/profile.blade.php'), [
            'user' => $user,
        ])->render();
        $this->assertStringContainsString('username', $profileView);
        $this->assertStringContainsString('first_name', $profileView);
        $this->assertStringContainsString('last_name', $profileView);
    }

    /**
     * Test that the CMS Framework permissions system is correctly integrated.
     *
     * @return void
     */
    public function test_cms_framework_permissions_system_integration()
    {
        // Create roles and permissions
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        
        $viewDashboardPermission = Permission::create(['name' => 'view_dashboard', 'display_name' => 'View Dashboard']);
        $manageUsersPermission = Permission::create(['name' => 'manage_users', 'display_name' => 'Manage Users']);
        $createContentPermission = Permission::create(['name' => 'create_content', 'display_name' => 'Create Content']);
        $editContentPermission = Permission::create(['name' => 'edit_content', 'display_name' => 'Edit Content']);
        
        // Assign permissions to roles
        $adminRole->assignPermission($viewDashboardPermission);
        $adminRole->assignPermission($manageUsersPermission);
        $adminRole->assignPermission($createContentPermission);
        $adminRole->assignPermission($editContentPermission);
        
        $editorRole->assignPermission($viewDashboardPermission);
        $editorRole->assignPermission($createContentPermission);
        $editorRole->assignPermission($editContentPermission);
        
        // Create users with roles
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);
        
        $editorUser = User::factory()->create();
        $editorUser->assignRole($editorRole);
        
        // Test admin permissions
        $this->assertTrue($adminUser->hasPermission('view_dashboard'));
        $this->assertTrue($adminUser->hasPermission('manage_users'));
        $this->assertTrue($adminUser->hasPermission('create_content'));
        $this->assertTrue($adminUser->hasPermission('edit_content'));
        
        // Test editor permissions
        $this->assertTrue($editorUser->hasPermission('view_dashboard'));
        $this->assertFalse($editorUser->hasPermission('manage_users'));
        $this->assertTrue($editorUser->hasPermission('create_content'));
        $this->assertTrue($editorUser->hasPermission('edit_content'));
        
        // Test permission checks in controllers
        $this->actingAs($adminUser);
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        
        $this->actingAs($editorUser);
        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    /**
     * Test that the CMS Framework content types are correctly integrated.
     *
     * @return void
     */
    public function test_cms_framework_content_types_integration()
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Configure content types
        config(['cms-framework.content_types' => [
            'page' => [
                'name' => 'Page',
                'name_plural' => 'Pages',
                'fields' => [
                    'title' => [
                        'type' => 'text',
                        'required' => true,
                    ],
                    'content' => [
                        'type' => 'wysiwyg',
                        'required' => true,
                    ],
                ],
                'statuses' => ['draft', 'published', 'archived'],
                'default_status' => 'draft',
            ],
            'article' => [
                'name' => 'Article',
                'name_plural' => 'Articles',
                'fields' => [
                    'title' => [
                        'type' => 'text',
                        'required' => true,
                    ],
                    'content' => [
                        'type' => 'wysiwyg',
                        'required' => true,
                    ],
                    'category' => [
                        'type' => 'select',
                        'options' => ['News', 'Tutorial', 'Opinion'],
                        'required' => true,
                    ],
                ],
                'statuses' => ['draft', 'review', 'published', 'archived'],
                'default_status' => 'draft',
            ],
        ]]);
        
        // Create content items
        $page = Content::create([
            'type' => 'page',
            'title' => 'Test Page',
            'content' => '<p>This is a test page.</p>',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);
        
        $article = Content::create([
            'type' => 'article',
            'title' => 'Test Article',
            'content' => '<p>This is a test article.</p>',
            'category' => 'News',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);
        
        // Test content retrieval
        $pages = Content::whereType('page')->get();
        $this->assertCount(1, $pages);
        $this->assertEquals('Test Page', $pages->first()->title);
        
        $articles = Content::whereType('article')->get();
        $this->assertCount(1, $articles);
        $this->assertEquals('Test Article', $articles->first()->title);
        $this->assertEquals('News', $articles->first()->category);
        
        // Test content update
        $page->update([
            'title' => 'Updated Page',
            'status' => 'published',
        ]);
        
        $this->assertEquals('Updated Page', $page->fresh()->title);
        $this->assertEquals('published', $page->fresh()->status);
        
        // Test content deletion
        $article->delete();
        $this->assertCount(0, Content::whereType('article')->get());
    }
}