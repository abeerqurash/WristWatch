<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Replace the public cloned demo content while retaining the original records. */
class WristWatchStorefrontSeeder extends Seeder
{
    public function run(): void
    {
        $backup = 'wristwatch/original-storefront.json';
        if (!Storage::disk('local')->exists($backup)) {
            Storage::disk('local')->put($backup, json_encode([
                'products' => Product::all()->toArray(), 'product_categories' => ProductCategory::all()->toArray(),
                'posts' => Post::all()->toArray(), 'categories' => Category::all()->toArray(),
                'product_category_product' => DB::table('product_category_product')->get()->toArray(),
                'category_post' => DB::table('category_post')->get()->toArray(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        DB::transaction(function () {
            $collections = [];
            foreach (['Mens', 'Womens', 'Jewelry', 'Accessories', 'New Releases'] as $title) {
                $collections[$title] = ProductCategory::updateOrCreate(['slug' => Str::slug($title)], ['title' => $title, 'parent_id' => null, 'description' => 'Nam tempus turpis at metus scelerisque placerat nulla deumantos sollicitudin delos felis. Explore the WristWatch collection.', 'featured_image' => 'storefront/images/' . ($title === 'Womens' ? 'womens.jpg' : 'mens.jpg')]);
            }
            $watches = [
                ['Ampus Cosmo De Milancelos Scelerisque',899,839,'2051028__60203__75982.1583120657.jpg','2051370__71885__41955.1583120657.jpg','Mens'],
                ['Minterdum AnDacony Maliduet',1299,899,'2232049__80153__76537.1583120669.jpg','1737694__02786__66713.1583120669.jpg','Womens'],
                ['Nullam commodo merato dano cosmopolis',869.99,null,'1930125__30211.1576203672.1280.1280__82161__97257.1583120656.jpg','1365150__01234.1576203671.1280.1280__38112__46290.1583120656.jpg','Mens'],
                ['Nullam accumsan tincidunt',899,869.99,'2050986__03546.1576206570.1280.1280__56478__83639.1583120686.jpg','2051370__97205.1576206570.1280.1280__74113__74392.1583120686.jpg','Mens'],
                ['Turpis At Metus Scelerisque Placerat',469,null,'9413448__57789.1576203438.1280.1280__51542__22538.1583120671.jpg','2051362__97798__03963.1583120671.jpg','Mens'],
                ['Dulla Minterdum Dacony',1299,1099,'2052415__77514.1576204623.1280.1280__36699.1576212550.1280.1280__69899__32976.1583120669.jpg',null,'Jewelry'],
                ['Diverra Dulla Minterdum AnDacony Maliduet',899.99,869.99,'2332744__19213__70409.1583120667.jpg',null,'Mens'],
                ['Ninterdum Pre De Condimento',630,499.99,'2051079__54421__75373.1583120661.jpg',null,'Womens'],
                ['Tempus Turpis At Metus Scelerisque',899,699,'1736337__45030__64580.1583120671.jpg',null,'Accessories'],
                ['Elementum Etos Lobortis',489,null,'1738046__77895.1576204144.1280.1280__94571__94581.1583120680.jpg',null,'Womens'],
            ];
            $watchIds = [];
            foreach ($watches as $index => [$title, $regular, $sale, $image, $alternate, $collection]) {
                $product = Product::updateOrCreate(['slug' => Str::slug($title)], [
                    'title'=>$title,'sku'=>'WW-'.str_pad((string)($index+1),3,'0',STR_PAD_LEFT),'regular_price'=>$regular,'sale_price'=>$sale,
                    'featured_image'=>'storefront/images/'.$image,'stock'=>$index===6?0:25,'status'=>'active','is_featured'=>$index>=4,
                    'short_description'=>'Nam tempus turpis at metus scelerisque placerat nulla deumantos sollicitudin delos felis. Pellentesque diam dolor an elementum et lobortis at mollis ut risus.',
                    'long_description'=>'<p>Nam tempus turpis at metus scelerisque placerat nulla deumantos sollicitudin delos felis. Pellentesque diam dolor an elementum et lobortis at mollis ut risus. Curabitur semper sagittis mino de condimentum.</p><h3>The Specs</h3><p>A timeless watch selected for its elegant design, distinctive dial and refined finish.</p><h3>Metropolis</h3><p>Quisquemos sodales suscipit tortor ditaemcos milancelos condimentum de cosmo lacus meleifend blanditos.</p>',
                    'additional_info'=>'<p>Collection: '.e($collection).'</p><p>Condition: New</p>','meta_title'=>$title.' | WristWatch',
                ]);
                $product->categories()->sync([$collections[$collection]->id,$collections['New Releases']->id]);
                $product->images()->firstOrCreate(['image'=>'storefront/images/'.$image],['sort_order'=>0]);
                if ($alternate) $product->images()->firstOrCreate(['image'=>'storefront/images/'.$alternate],['sort_order'=>1]);
                if (in_array($index, [0, 2, 4], true)) {
                    $option = \App\Models\ProductOption::firstOrCreate(['name'=>'Color'],['type'=>'select']);
                    $valueIds = [];
                    foreach (['Black','Blue'] as $color) {
                        $value = \App\Models\ProductOptionValue::firstOrCreate(['product_option_id'=>$option->id,'value'=>$color],['label'=>$color]);
                        $valueIds[] = $value->id;
                        $product->variants()->updateOrCreate(['sku'=>$product->sku.'-'.strtoupper($color)],[
                            'regular_price'=>$regular,'sale_price'=>$sale,'stock'=>12,
                            'image'=>'storefront/images/'.($color==='Blue' && $alternate ? $alternate : $image),
                            'options'=>[['option_id'=>$option->id,'option_name'=>'Color','value_id'=>$value->id,'value_label'=>$color]],
                        ]);
                    }
                    $product->options()->sync([$option->id]);
                    $product->optionValues()->sync($valueIds);
                }
                $watchIds[] = $product->id;
            }
            Product::whereNotIn('id',$watchIds)->where('status','active')->update(['status'=>'inactive']);
            $topics = [];
            foreach (['Collections','Luxury Watches','Mens Watches','Womens Watches'] as $title) {
                $topics[] = Category::updateOrCreate(['slug'=>Str::slug($title)],['title'=>$title,'image'=>'storefront/images/mens.jpg']);
            }
            $postIds = [];
            foreach (['Dinterdum pretium es loremous dorus condimentus','Loremous Comodous: Trending','Commodo Muso Magna Cosmopolis','A guide to timeless watch collections','The art of choosing your everyday watch','Discover the latest WristWatch releases'] as $index=>$title) {
                $post = Post::updateOrCreate(['slug'=>Str::slug($title)],[
                    'title'=>$title,'status'=>'published','published_at'=>now()->subDays($index),'primary_category_id'=>$topics[$index%4]->id,
                    'feature_image'=>$index===0?null:'storefront/images/'.($index===1?'blog-img-2.jpg':($index%2?'womens.jpg':'mens.jpg')),
                    'excerpt'=>'Nam suscipit mollis tellus vel malesuada. Duis danos an molestie, sem in sollicitudin sodales mi justo sagittis est id consequat ipsum ligula a ante.',
                    'content'=>'<p>Nam suscipit mollis tellus vel malesuada. Duis danos an molestie, sem in sollicitudin sodales mi justo sagittis est id consequat ipsum ligula a ante.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis risus leo, elementum in malesuada ut augue.</p><h3>Sample Unordered List</h3><ul><li>Comodous in tempor ullamcorper miaculis.</li><li>Pellentesque vitae neque mollis urna mattis laoreet.</li><li>Divamus sit amet purus justo.</li></ul><h3>Sample Ordered List</h3><ol><li>Comodous in tempor ullamcorper miaculis.</li><li>Pellentesque vitae neque mollis urna mattis laoreet.</li><li>Divamus sit amet purus justo.</li></ol><blockquote>Nam tempus turpis at metus scelerisque placerat nulla deumantos solicitud felis.</blockquote><h3>Womens Watches</h3><p>Discover elegant details and timeless designs in our collection.</p><h3>Mens Watches</h3><p>Explore distinctive dials and classic finishes for every occasion.</p>',
                ]);
                $post->categories()->sync([$topics[$index%4]->id]);
                $postIds[] = $post->id;
            }
            Post::whereNotIn('id',$postIds)->whereIn('status',['published','scheduled'])->update(['status'=>'archived']);
        });
    }
}


