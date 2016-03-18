@include('elements.side-menu-parent-item', [
'folder' => 'recruitment-management',
'menu' => 'Recruitment',
'menuIcon' => 'fa-sign-in',
'children' => [ [
        'page' => 'settings',
        'url' => 'settings',
        'menu' => 'Settings',
    ], [
        'page' => 'list-recruitments',
        'url' => 'list-recruitments/' . $auth->user->getRouteKey(),
        'menu' => 'List Recruitments',
    ]
]])
