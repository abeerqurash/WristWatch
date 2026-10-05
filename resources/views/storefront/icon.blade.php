<svg class="ww-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true">
@switch($icon)
@case('menu')<path d="M2 5h20M2 12h20M2 19h20"/>@break
@case('search')<circle cx="10" cy="10" r="7"/><path d="m15 15 6 6"/>@break
@case('user')<circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/>@break
@case('cart')<path d="M2 3h3l3 13h11l3-10H6"/><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>@break
@case('heart')<path d="M12 21 3 12C-3 5 6-1 12 6c6-7 15-1 9 6Z"/>@break
@case('truck')<path d="M1 4h13v13H1ZM14 9h5l4 5v3h-9"/><circle cx="6" cy="19" r="2"/><circle cx="18" cy="19" r="2"/>@break
@case('return')<path d="M5 7a9 9 0 1 1-1 10M5 2v6H0"/>@break
@case('shield')<path d="m12 2 9 4v7c0 5-9 9-9 9s-9-4-9-9V6Z"/><path d="m7 12 3 3 7-7"/>@break
@case('lock')<rect x="4" y="10" width="16" height="12"/><path d="M7 10V7a5 5 0 0 1 10 0v3M12 14v4"/>@break
@endswitch
</svg>
