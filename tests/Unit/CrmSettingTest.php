<?php

namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\CrmSetting;

class CrmSettingTest extends TestCase
{
    /** @test */
    public function it_can_create_a_crm_setting()
    {
        $setting = CrmSetting::create([
            'key' => 'test_setting',
            'value' => 'test_value',
            'type' => 'text',
            'group' => 'test',
        ]);

        $this->assertDatabaseHas('crm_settings', [
            'key' => 'test_setting',
        ]);

        $this->assertEquals('test_value', $setting->value);
    }

    /** @test */
    public function it_can_get_setting_value_by_key()
    {
        CrmSetting::create([
            'key' => 'test_key',
            'value' => 'test_value',
            'type' => 'text',
            'group' => 'test',
        ]);

        $value = CrmSetting::get('test_key');

        $this->assertEquals('test_value', $value);
    }

    /** @test */
    public function it_returns_default_value_when_setting_not_found()
    {
        $value = CrmSetting::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    /** @test */
    public function it_handles_translatable_settings()
    {
        CrmSetting::create([
            'key' => 'translatable_setting',
            'value' => ['en' => 'English Value', 'ar' => 'قيمة عربية'],
            'type' => 'text',
            'group' => 'test',
            'is_translatable' => true,
        ]);

        app()->setLocale('en');
        $this->assertEquals('English Value', CrmSetting::get('translatable_setting'));

        app()->setLocale('ar');
        $this->assertEquals('قيمة عربية', CrmSetting::get('translatable_setting'));
    }

    /** @test */
    public function it_can_set_a_setting()
    {
        CrmSetting::set('new_setting', 'new_value', 'text', 'general');

        $this->assertDatabaseHas('crm_settings', [
            'key' => 'new_setting',
            'value' => json_encode('new_value'),
        ]);
    }

    /** @test */
    public function it_updates_existing_setting()
    {
        CrmSetting::create([
            'key' => 'update_test',
            'value' => 'old_value',
            'type' => 'text',
            'group' => 'test',
        ]);

        CrmSetting::set('update_test', 'new_value');

        $setting = CrmSetting::where('key', 'update_test')->first();
        $this->assertEquals('new_value', $setting->value);
    }

    /** @test */
    public function it_can_get_all_settings_grouped()
    {
        CrmSetting::create([
            'key' => 'contact_phone',
            'value' => '+1234567890',
            'type' => 'text',
            'group' => 'contact',
            'order' => 1,
        ]);

        CrmSetting::create([
            'key' => 'business_name',
            'value' => 'Test Business',
            'type' => 'text',
            'group' => 'business',
            'order' => 1,
        ]);

        $grouped = CrmSetting::getAllGrouped();

        $this->assertArrayHasKey('contact', $grouped);
        $this->assertArrayHasKey('business', $grouped);
        $this->assertEquals('+1234567890', $grouped['contact']['contact_phone']);
        $this->assertEquals('Test Business', $grouped['business']['business_name']);
    }

    /** @test */
    public function it_respects_order_in_grouped_settings()
    {
        CrmSetting::create(['key' => 'setting_3', 'value' => '3', 'group' => 'test', 'order' => 3]);
        CrmSetting::create(['key' => 'setting_1', 'value' => '1', 'group' => 'test', 'order' => 1]);
        CrmSetting::create(['key' => 'setting_2', 'value' => '2', 'group' => 'test', 'order' => 2]);

        $settings = CrmSetting::where('group', 'test')->orderBy('order')->get();

        $this->assertEquals('setting_1', $settings[0]->key);
        $this->assertEquals('setting_2', $settings[1]->key);
        $this->assertEquals('setting_3', $settings[2]->key);
    }
}
