<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run()
    {
        Page::factory()->create([
            'slug' => 'about',
            'title' => 'About Us',
        ]);

        Page::factory()->create([
            'slug' => 'terms',
            'title' => 'Terms & Conditions',
        ]);

        Menu::create([
            'name' => 'main',
            'items' => [
                [
                    'title' => 'Blog',
                    'url' => '/blog',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'About',
                    'url' => '/about',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'Contact',
                    'url' => '/contact',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'Shop',
                    'url' => '/shop',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
                [
                    'title' => 'Cart',
                    'url' => '/cart',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
                [
                    'title' => 'Checkout',
                    'url' => '/checkout',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
        ]]);
        Menu::create([
            'name' => 'footer',
            'items' => [
                [
                    'title' => 'Terms & Conditions',
                    'url' => '/terms',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                              [
                    'title' => 'Blog',
                    'url' => '/blog',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'About',
                    'url' => '/about',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'Contact',
                    'url' => '/contact',
                    'type' => 'internal',
                    'group' => 'contact',
                ],
                [
                    'title' => 'Shop',
                    'url' => '/shop',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
                [
                    'title' => 'Cart',
                    'url' => '/cart',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
                [
                    'title' => 'Checkout',
                    'url' => '/checkout',
                    'type' => 'internal',
                    'group' => 'shop',
                ],
            ],
        ]);
    }
}
