@php
    $breadcrumbs = $breadcrumbs ?? [];
    
    if (empty($breadcrumbs)) {
        // Default breadcrumbs based on current route
        $currentRoute = request()->route()->getName();
        $segments = explode('.', $currentRoute);
        
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'active' => $currentRoute === 'dashboard'],
        ];
        
        if (count($segments) > 1) {
            $resource = $segments[0];
            $action = $segments[1];
            
            $resourceLabels = [
                'jobs' => 'Jobs',
                'applications' => 'Applications',
                'companies' => 'Companies',
                'profile' => 'Profile',
                'settings' => 'Settings',
            ];
            
            $actionLabels = [
                'index' => 'List',
                'create' => 'Create',
                'edit' => 'Edit',
                'show' => 'Detail',
            ];
            
            if (isset($resourceLabels[$resource])) {
                $breadcrumbs[] = [
                    'label' => $resourceLabels[$resource],
                    'url' => route($resource . '.index'),
                    'active' => $action === 'index'
                ];
                
                if ($action !== 'index' && isset($actionLabels[$action])) {
                    $breadcrumbs[] = [
                        'label' => $actionLabels[$action],
                        'url' => '#',
                        'active' => true
                    ];
                }
            }
        }
    }
@endphp

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach ($breadcrumbs as $index => $crumb)
            <li class="inline-flex items-center">
                @if ($index > 0)
                    <svg class="w-4 h-4 mx-2 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
                
                @if ($crumb['active'] ?? false)
                    <span class="text-sm font-medium text-primary-600">
                        {{ $crumb['label'] }}
                    </span>
                @else
                    <a href="{{ $crumb['url'] }}" class="text-sm font-medium text-neutral-500 hover:text-primary-600 transition-colors">
                        {{ $crumb['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>