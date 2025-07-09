<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Insert predefined post categories into the 'post_categories' table.
        // These categories are essential for organizing website content.
        DB::table('post_categories')->insert([
            [
            'name' => 'الخدمات',
            'slug' => 'services',
            'description' => 'نقدم مجموعة واسعة من الخدمات المتخصصة لتلبية احتياجات عملائنا.'
            ],
            [
            'name' => 'اعمالنا',
            'slug' => 'our_works',
            'description' => 'استعراض لأحدث مشاريعنا والأعمال التي قمنا بتنفيذها بنجاح.'
            ],
            [
            'name' => 'المدونة',
            'slug' => 'blogs',
            'description' => 'مقالات ومدونات حول مواضيع تهمك ونصائح وإرشادات مفيدة.'
            ],
            [
            'name' => 'الأسئلة الشائعة',
            'slug' => 'faqs',
            'description' => 'إجابات على الأسئلة الأكثر تداولاً لمساعدتك في الحصول على معلومات سريعة.'
            ],
            [
            'name' => 'الاسعار',
            'slug' => 'prices',
            'description' => 'قائمة بأسعار خدماتنا المختلفة لتكون على علم بتكاليفنا.'
            ],
        ]);
    }
}
//  add suitable component_view from @database\seeders\HomepageSectionSeeder.php to first post in each @database\seeders\NewSiteSeeder.php post 
//   ,and also convert the content to markdown content type without change it