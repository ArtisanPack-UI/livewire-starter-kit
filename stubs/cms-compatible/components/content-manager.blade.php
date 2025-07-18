@props([
    'contentType' => null,
    'contentId' => null,
    'showTitle' => true,
    'showActions' => true,
    'showStatus' => true,
    'allowEdit' => true,
    'allowDelete' => true,
    'allowPublish' => true,
    'allowDuplicate' => false,
    'inline' => false,
    'compact' => false,
])

@php
    // Get the content type configuration
    $contentTypeConfig = config("cms-framework.content_types.{$contentType}", null);
    
    // Get the content item if contentId is provided
    $contentItem = null;
    if ($contentId && $contentType) {
        // This would use the CMS Framework's content retrieval system
        // For example: $contentItem = \ArtisanpackUi\CmsFramework\Models\Content::findByTypeAndId($contentType, $contentId);
        
        // For demonstration, we'll assume the content item is retrieved
        $contentItem = (object)[
            'id' => $contentId,
            'title' => 'Sample Content Item',
            'status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
            'published_at' => now(),
            'author' => auth()->user(),
        ];
    }
    
    // Determine if the current user has permission to perform actions
    $user = auth()->user();
    $canEdit = $allowEdit && $user && ($user->hasPermission("edit_{$contentType}") || $user->hasPermission('edit_all_content'));
    $canDelete = $allowDelete && $user && ($user->hasPermission("delete_{$contentType}") || $user->hasPermission('delete_all_content'));
    $canPublish = $allowPublish && $user && ($user->hasPermission("publish_{$contentType}") || $user->hasPermission('publish_all_content'));
    $canDuplicate = $allowDuplicate && $user && ($user->hasPermission("create_{$contentType}") || $user->hasPermission('create_all_content'));
    
    // Status colors
    $statusColors = [
        'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'published' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'archived' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    ];
    
    $statusColor = $contentItem && isset($statusColors[$contentItem->status]) 
        ? $statusColors[$contentItem->status] 
        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
@endphp

<div class="cms-content-manager {{ $inline ? 'inline-flex items-center' : 'block' }} {{ $compact ? 'space-y-2' : 'space-y-4' }}">
    @if(!$contentType)
        <div class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 p-4 rounded-md">
            Content type is required for the content manager component.
        </div>
    @elseif(!$contentTypeConfig)
        <div class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 p-4 rounded-md">
            Content type "{{ $contentType }}" is not configured in the CMS Framework.
        </div>
    @else
        @if($contentId && !$contentItem)
            <div class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 p-4 rounded-md">
                Content item with ID "{{ $contentId }}" not found.
            </div>
        @else
            <div class="cms-content-manager-container {{ $inline ? 'flex items-center space-x-4' : '' }}">
                @if($contentItem && $showTitle)
                    <div class="cms-content-title {{ $compact ? 'text-base' : 'text-lg font-medium' }} text-gray-900 dark:text-gray-100">
                        {{ $contentItem->title }}
                    </div>
                @endif
                
                @if($contentItem && $showStatus)
                    <div class="cms-content-status {{ $inline ? 'ml-2' : '' }}">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                            {{ ucfirst($contentItem->status) }}
                        </span>
                    </div>
                @endif
                
                @if($showActions && ($canEdit || $canDelete || $canPublish || $canDuplicate))
                    <div class="cms-content-actions {{ $inline ? 'ml-auto' : 'mt-2' }} flex items-center space-x-2">
                        @if($canEdit)
                            <x-artisanpack-button 
                                size="sm" 
                                href="{{ route('cms.content.edit', ['type' => $contentType, 'id' => $contentItem ? $contentItem->id : null]) }}"
                                variant="secondary"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                {{ $compact ? '' : 'Edit' }}
                            </x-artisanpack-button>
                        @endif
                        
                        @if($canPublish && $contentItem && $contentItem->status !== 'published')
                            <x-artisanpack-button 
                                size="sm" 
                                href="{{ route('cms.content.publish', ['type' => $contentType, 'id' => $contentItem->id]) }}"
                                variant="success"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $compact ? '' : 'Publish' }}
                            </x-artisanpack-button>
                        @endif
                        
                        @if($canDuplicate && $contentItem)
                            <x-artisanpack-button 
                                size="sm" 
                                href="{{ route('cms.content.duplicate', ['type' => $contentType, 'id' => $contentItem->id]) }}"
                                variant="secondary"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                    <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                                </svg>
                                {{ $compact ? '' : 'Duplicate' }}
                            </x-artisanpack-button>
                        @endif
                        
                        @if($canDelete && $contentItem)
                            <x-artisanpack-button 
                                size="sm" 
                                href="{{ route('cms.content.delete', ['type' => $contentType, 'id' => $contentItem->id]) }}"
                                variant="danger"
                                x-data="{}"
                                x-on:click.prevent="if (confirm('Are you sure you want to delete this item?')) { window.location.href = $el.getAttribute('href'); }"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $compact ? '' : 'Delete' }}
                            </x-artisanpack-button>
                        @endif
                    </div>
                @endif
                
                @if(!$contentId)
                    <div class="cms-content-create {{ $inline ? 'ml-auto' : 'mt-2' }}">
                        <x-artisanpack-button 
                            href="{{ route('cms.content.create', ['type' => $contentType]) }}"
                            variant="primary"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Create {{ ucfirst($contentType) }}
                        </x-artisanpack-button>
                    </div>
                @endif
            </div>
            
            @if($contentItem && !$compact)
                <div class="cms-content-meta text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex flex-wrap gap-x-4 gap-y-1">
                        <span>Created: {{ $contentItem->created_at->format('M d, Y') }}</span>
                        <span>Updated: {{ $contentItem->updated_at->format('M d, Y') }}</span>
                        @if($contentItem->status === 'published' && $contentItem->published_at)
                            <span>Published: {{ $contentItem->published_at->format('M d, Y') }}</span>
                        @endif
                        @if($contentItem->author)
                            <span>Author: {{ $contentItem->author->name }}</span>
                        @endif
                    </div>
                </div>
            @endif
            
            @if($slot->isNotEmpty())
                <div class="cms-content-custom {{ $compact ? 'mt-2' : 'mt-4' }}">
                    {{ $slot }}
                </div>
            @endif
        @endif
    @endif
</div>