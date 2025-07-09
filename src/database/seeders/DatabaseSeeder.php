<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        // DB::table('post_categories')->insert([
        //     ['name' => 'الخدمات', 'slug' => 'services'],
        //     ['name' => 'اعمالنا', 'slug' => 'our_works'],
        //     ['name' => 'المدونة', 'slug' => 'blogs'],
        //     ['name' => 'الأسئلة الشائعة', 'slug' => 'faqs'],
        // ]);

        // Post::factory()
        //     ->count(10)
        //     ->create();

        $this->call(NewSiteSeeder::class);

        $this->call(HomepageSectionSeeder::class);

        Notification::make()
            ->title('Welcome to Filament')
            ->body('You are ready to start building your application.')
            ->success()
            ->sendToDatabase(User::first());
    }
}
