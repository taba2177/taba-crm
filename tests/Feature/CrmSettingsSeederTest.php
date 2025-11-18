<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Database\Seeders\CrmSettingsSeeder;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class CrmSettingsSeederTest extends TestCase
{
    /** @test */
    public function it_seeds_all_required_settings()
    {
        $this->seed(CrmSettingsSeeder::class);

        // Contact settings
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_phone']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_email']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_address']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_city']);

        // Social settings
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_facebook']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_twitter']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_contact_instagram']);

        // Business settings
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_business_name']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_business_opens']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_business_closes']);

        // SEO settings
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_seo_default_title']);
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_seo_default_description']);

        // API settings
        $this->assertDatabaseHas('crm_settings', ['key' => 'crm_gemini_api_key']);
    }

    /** @test */
    public function it_uses_post_data_for_seo_defaults()
    {
        $category = PostCategory::factory()->create(['name' => 'Test Category']);
        $post = Post::factory()->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'status' => 'published',
            'meta_title' => 'SEO Title from Post',
            'meta_description' => 'SEO Description from Post',
            'order' => 1,
        ]);

        $this->seed(CrmSettingsSeeder::class);

        $seoTitle = CrmSetting::where('key', 'crm_seo_default_title')->first();
        $seoDescription = CrmSetting::where('key', 'crm_seo_default_description')->first();

        $this->assertStringContainsString('SEO Title from Post', json_encode($seoTitle->value));
        $this->assertStringContainsString('SEO Description from Post', json_encode($seoDescription->value));
    }

    /** @test */
    public function it_falls_back_to_category_when_no_posts()
    {
        $category = PostCategory::factory()->create([
            'name' => 'Fallback Category',
            'description' => 'Fallback Description',
            'order' => 1,
        ]);

        $this->seed(CrmSettingsSeeder::class);

        $seoTitle = CrmSetting::where('key', 'crm_seo_default_title')->first();

        $this->assertStringContainsString('Fallback Category', json_encode($seoTitle->value));
    }

    /** @test */
    public function it_sets_correct_groups_for_settings()
    {
        $this->seed(CrmSettingsSeeder::class);

        $contactSetting = CrmSetting::where('key', 'crm_contact_phone')->first();
        $this->assertEquals('contact', $contactSetting->group);

        $socialSetting = CrmSetting::where('key', 'crm_contact_facebook')->first();
        $this->assertEquals('social', $socialSetting->group);

        $businessSetting = CrmSetting::where('key', 'crm_business_name')->first();
        $this->assertEquals('business', $businessSetting->group);

        $seoSetting = CrmSetting::where('key', 'crm_seo_default_title')->first();
        $this->assertEquals('seo', $seoSetting->group);

        $apiSetting = CrmSetting::where('key', 'crm_gemini_api_key')->first();
        $this->assertEquals('api', $apiSetting->group);
    }

    /** @test */
    public function it_marks_translatable_fields_correctly()
    {
        $this->seed(CrmSettingsSeeder::class);

        $translatableSetting = CrmSetting::where('key', 'crm_contact_address')->first();
        $this->assertTrue($translatableSetting->is_translatable);

        $nonTranslatableSetting = CrmSetting::where('key', 'crm_contact_phone')->first();
        $this->assertFalse($nonTranslatableSetting->is_translatable);
    }

    /** @test */
    public function it_respects_order_in_seeded_settings()
    {
        $this->seed(CrmSettingsSeeder::class);

        $contactSettings = CrmSetting::where('group', 'contact')->orderBy('order')->get();

        $this->assertGreaterThan(0, $contactSettings->count());
        
        // Verify order is sequential
        $previousOrder = 0;
        foreach ($contactSettings as $setting) {
            $this->assertGreaterThanOrEqual($previousOrder, $setting->order);
            $previousOrder = $setting->order;
        }
    }

    /** @test */
    public function it_can_be_run_multiple_times_without_duplicates()
    {
        $this->seed(CrmSettingsSeeder::class);
        $firstCount = CrmSetting::count();

        $this->seed(CrmSettingsSeeder::class);
        $secondCount = CrmSetting::count();

        $this->assertEquals($firstCount, $secondCount);
    }
}
