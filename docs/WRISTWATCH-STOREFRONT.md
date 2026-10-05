# WristWatch storefront

Reference: https://luxwatches-demo-02.mybigcommerce.com/

## Reference audit

Inspected home, About, blog listing/article, Mens category, product detail and Contact. Saved reference photography under `public/storefront/images`.

The reference uses Poppins, black navigation, a cream announcement strip, watch slides, thin borders, red sale prices, white cards, muted metadata, category sidebars, mobile navigation and a photographic newsletter/footer.

## Pages and behavior

- Home; About (`/about-us` alias); Contact (`/contact-us` alias).
- Catalog, product categories, category catalogs, product details and quick view.
- Blog listing, blog cards, category listing/detail and individual articles.
- Cart, wish list, existing checkout and authentication entry pages.
- Brands, lookbook, image gallery, FAQs, shipping/returns and existing CMS policies.

Catalog search, filters, sorting and pagination use the original shop controller. Page size accepts 12, 24 or 36. Product variants, cart, quantities, coupons, wish lists and moderated reviews use the original commerce backend. Quick view shares the detail form. Newsletter subscriptions are stored locally in `form_submissions`; no marketing provider is connected. Contact uses the original validated form handler. Checkout/payment logic remains intact.

Dashboard templates/controllers were not redesigned. Public articles now render published database content without requiring a separate manually authored Blade file.

## Demo content and recovery

At the user's request, the public catalog was replaced with 10 watches, 5 collections and 6 articles. Three watches have color variants; one is sold out. Old active products became inactive and old published/scheduled posts became archived. Original records remain; old categories are hidden publicly unless they contain active/published content.

The pre-change product/post/category records and category pivot data are backed up on local storage at `wristwatch/original-storefront.json`, normally `storage/app/private/wristwatch/original-storefront.json`. Preserve that file. To recover the original public content, restore saved statuses/category properties and hide the new fixtures. Orders, customer accounts and admin accounts were not replaced.

For a fresh clone, run `php artisan db:seed --class=WristWatchStorefrontSeeder`. Rerunning resets fixture prices, stock and content, so this is a demo setup seeder rather than a production content migration.

Latin copy, product descriptions, collection names, FAQs, opening hours and the reference map are placeholders. Replace them with actual specifications/store information before launch. The photographs are reference demo assets; confirm applicable asset/theme licensing before publishing. The reference has additional sample products, taxonomy nodes and article variations beyond these fixtures. This implementation recreates the page families and visual design rather than importing BigCommerce theme source/backend.

## Verification

- `node scripts/storefront-smoke.mjs`: 29 HTTP, asset and CSRF checks. Requires server at `127.0.0.1:8000`, or set `STOREFRONT_URL`.
- JavaScript/PHP syntax checks and `php artisan view:cache`.
- Browser: quick view; color selection and add to cart; cart/checkout continuity; article navigation; 390 px mobile navigation and overflow checks.

No paid order was submitted or real newsletter/contact message sent during verification.
