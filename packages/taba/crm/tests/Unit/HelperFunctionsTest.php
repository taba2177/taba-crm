<?php

namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\CrmSetting;

class HelperFunctionsTest extends TestCase
{
    /** @test */
    public function crm_setting_helper_returns_value()
    {
        CrmSetting::create([
            'key' => 'crm_test_key',
            'value' => 'test_value',
            'type' => 'text',
            'group' => 'test',
        ]);

        $this->assertEquals('test_value', crm_setting('test_key'));
        $this->assertEquals('test_value', crm_setting('crm_test_key'));
    }

    /** @test */
    public function crm_setting_helper_returns_default()
    {
        $this->assertEquals('default', crm_setting('non_existent', 'default'));
    }

    /** @test */
    public function crm_contact_helper_returns_all_contact_info()
    {
        CrmSetting::create(['key' => 'crm_contact_phone', 'value' => '+1234567890', 'group' => 'contact']);
        CrmSetting::create(['key' => 'crm_contact_email', 'value' => 'test@example.com', 'group' => 'contact']);

        $contact = crm_contact();

        $this->assertIsArray($contact);
        $this->assertEquals('+1234567890', $contact['phone']);
        $this->assertEquals('test@example.com', $contact['email']);
    }

    /** @test */
    public function crm_contact_helper_returns_specific_field()
    {
        CrmSetting::create(['key' => 'crm_contact_phone', 'value' => '+1234567890', 'group' => 'contact']);

        $this->assertEquals('+1234567890', crm_contact('phone'));
    }

    /** @test */
    public function crm_social_links_helper_returns_non_empty_links()
    {
        CrmSetting::create(['key' => 'crm_contact_facebook', 'value' => 'https://facebook.com/test', 'group' => 'social']);
        CrmSetting::create(['key' => 'crm_contact_twitter', 'value' => '', 'group' => 'social']);
        CrmSetting::create(['key' => 'crm_contact_instagram', 'value' => 'https://instagram.com/test', 'group' => 'social']);

        $links = crm_social_links();

        $this->assertIsArray($links);
        $this->assertCount(2, $links);
        $this->assertContains('https://facebook.com/test', $links);
        $this->assertContains('https://instagram.com/test', $links);
        $this->assertNotContains('', $links);
    }

    /** @test */
    public function crm_business_helper_returns_all_business_info()
    {
        CrmSetting::create(['key' => 'crm_business_name', 'value' => ['en' => 'Test Business'], 'group' => 'business', 'is_translatable' => true]);
        CrmSetting::create(['key' => 'crm_business_opens', 'value' => '09:00', 'group' => 'business']);

        $business = crm_business();

        $this->assertIsArray($business);
        $this->assertEquals('Test Business', $business['name']);
        $this->assertEquals('09:00', $business['opens']);
    }

    /** @test */
    public function crm_business_helper_returns_specific_field()
    {
        CrmSetting::create(['key' => 'crm_business_price_range', 'value' => 'SAR 100-1000', 'group' => 'business']);

        $this->assertEquals('SAR 100-1000', crm_business('price_range'));
    }

    /** @test */
    public function crm_business_name_falls_back_to_app_name()
    {
        config(['app.name' => 'Fallback App Name']);

        $this->assertEquals('Fallback App Name', crm_business('name'));
    }

    /** @test */
    public function helpers_handle_translatable_values()
    {
        CrmSetting::create([
            'key' => 'crm_contact_city',
            'value' => ['en' => 'Riyadh', 'ar' => 'الرياض'],
            'group' => 'contact',
            'is_translatable' => true,
        ]);

        app()->setLocale('en');
        $this->assertEquals('Riyadh', crm_contact('city'));

        app()->setLocale('ar');
        $this->assertEquals('الرياض', crm_contact('city'));
    }
}
