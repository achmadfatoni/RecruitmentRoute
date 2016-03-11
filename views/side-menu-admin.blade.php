@include('elements.side-menu-parent-item', [
'folder' => 'leaderboard',
'menu' => 'Leaderboard',
'menuIcon' => 'fa-signal',
'children' => [ [
'filter' => 'all',
'url' => 'top-recruitment-users/all',
'menu' => 'All',
], [
'filter' => 'monthly',
'url' => 'top-recruitment-users/monthly',
'menu' => 'Monthly',
]
]])