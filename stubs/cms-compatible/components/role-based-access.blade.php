@props([
    'role' => null,
    'permission' => null,
    'anyRole' => null,
    'allRoles' => null,
    'anyPermission' => null,
    'allPermissions' => null,
    'guest' => false,
    'auth' => false,
])

@php
    $shouldRender = false;
    $user = auth()->user();
    
    // Check authentication conditions
    if ($guest && !$user) {
        $shouldRender = true;
    } elseif ($auth && $user) {
        $shouldRender = true;
    }
    
    // If user is authenticated and no auth/guest check was specified, proceed with role/permission checks
    if ($user && !$shouldRender && !$guest) {
        // Single role check
        if ($role !== null) {
            $shouldRender = $user->hasRole($role);
        }
        
        // Single permission check
        if ($permission !== null && !$shouldRender) {
            $shouldRender = $user->hasPermission($permission);
        }
        
        // Check if user has any of the specified roles
        if ($anyRole !== null && !$shouldRender) {
            $roles = is_array($anyRole) ? $anyRole : explode('|', $anyRole);
            $shouldRender = $user->hasAnyRole($roles);
        }
        
        // Check if user has all of the specified roles
        if ($allRoles !== null && !$shouldRender) {
            $roles = is_array($allRoles) ? $allRoles : explode('|', $allRoles);
            $shouldRender = $user->hasAllRoles($roles);
        }
        
        // Check if user has any of the specified permissions
        if ($anyPermission !== null && !$shouldRender) {
            $permissions = is_array($anyPermission) ? $anyPermission : explode('|', $anyPermission);
            $shouldRender = $user->hasAnyPermission($permissions);
        }
        
        // Check if user has all of the specified permissions
        if ($allPermissions !== null && !$shouldRender) {
            $permissions = is_array($allPermissions) ? $allPermissions : explode('|', $allPermissions);
            $shouldRender = $user->hasAllPermissions($permissions);
        }
        
        // If no specific role/permission was checked, render for any authenticated user
        if ($role === null && $permission === null && $anyRole === null && $allRoles === null && 
            $anyPermission === null && $allPermissions === null && !$guest) {
            $shouldRender = true;
        }
    }
@endphp

@if($shouldRender)
    {{ $slot }}
@endif