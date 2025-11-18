# CRM Package Test Suite

Comprehensive test suite for the Taba CRM Laravel package covering all major functionality.

## 📋 Overview

The test suite includes:
- **Unit Tests**: Testing individual models, helpers, and components
- **Feature Tests**: Testing integrated functionality and workflows
- **Test Coverage**: Models, Seeders, Livewire Components, Helpers, Roles & Permissions

## 🧪 Test Structure

```
tests/
├── TestCase.php                          # Base test case with setup
├── Unit/
│   ├── CrmSettingTest.php               # CRM settings CRUD & retrieval
│   ├── HelperFunctionsTest.php          # Helper function tests
│   ├── UserModelTest.php                # User model & roles
│   ├── PostModelTest.php                # Post model & scopes
│   └── PostCategoryModelTest.php        # Category model & relationships
└── Feature/
    ├── CrmSettingsSeederTest.php        # Seeder functionality
    ├── HomeComponentTest.php            # Home Livewire component
    └── RolesAndPermissionsTest.php      # Role & permission integration
```

## 🚀 Running Tests

### Prerequisites

Install testing dependencies:
```bash
cd packages/taba/crm
composer install
```

### Run All Tests
```bash
composer test
# or
vendor/bin/phpunit
```

### Run Specific Test Suites
```bash
# Unit tests only
vendor/bin/phpunit --testsuite=Unit

# Feature tests only
vendor/bin/phpunit --testsuite=Feature
```

### Run Specific Test File
```bash
vendor/bin/phpunit tests/Unit/CrmSettingTest.php
```

### Run With Coverage Report
```bash
composer test-coverage
# Report generated in: build/coverage/index.html
```

### Run Specific Test Method
```bash
vendor/bin/phpunit --filter=it_can_create_a_crm_setting
```

## 📊 Test Coverage

### Unit Tests (45 tests)

#### CrmSettingTest.php (8 tests)
- ✅ Create CRM settings
- ✅ Get setting by key
- ✅ Default value fallback
- ✅ Translatable settings (EN/AR)
- ✅ Set/Update settings
- ✅ Get all settings grouped
- ✅ Respect order in grouped settings
- ✅ Handle JSON values

#### HelperFunctionsTest.php (9 tests)
- ✅ `crm_setting()` helper returns value
- ✅ `crm_setting()` returns default
- ✅ `crm_contact()` returns all info
- ✅ `crm_contact()` returns specific field
- ✅ `crm_social_links()` filters empty values
- ✅ `crm_business()` returns all info
- ✅ `crm_business()` returns specific field
- ✅ `crm_business('name')` fallback to app name
- ✅ Helpers handle translatable values

#### UserModelTest.php (5 tests)
- ✅ Create user
- ✅ Hash password on creation
- ✅ Assign roles to user
- ✅ Create super admin user
- ✅ Uses HasRoles trait

#### PostModelTest.php (7 tests)
- ✅ Create post
- ✅ Belongs to category relationship
- ✅ Scope published posts
- ✅ Filter posts for home
- ✅ Handle translatable fields (EN/AR)
- ✅ Has meta fields for SEO
- ✅ Order posts correctly

#### PostCategoryModelTest.php (7 tests)
- ✅ Create category
- ✅ Has many posts relationship
- ✅ Get published posts count
- ✅ Handle translatable fields (EN/AR)
- ✅ Has section component
- ✅ Order categories correctly
- ✅ Get first post relationship

### Feature Tests (22 tests)

#### CrmSettingsSeederTest.php (9 tests)
- ✅ Seeds all required settings
- ✅ Uses post data for SEO defaults
- ✅ Falls back to category when no posts
- ✅ Sets correct groups for settings
- ✅ Marks translatable fields correctly
- ✅ Respects order in seeded settings
- ✅ Can run multiple times without duplicates
- ✅ Contact, social, business, SEO, API settings
- ✅ Integration with Post & PostCategory models

#### HomeComponentTest.php (8 tests)
- ✅ Loads categories with sections
- ✅ Prepares SEO data from first post
- ✅ Falls back to category for SEO
- ✅ Identifies heavy sections (>6 posts)
- ✅ Creates fake posts for heavy sections
- ✅ Loads light sections immediately
- ✅ Can load remaining heavy posts
- ✅ Handles translatable content (EN/AR)

#### RolesAndPermissionsTest.php (8 tests)
- ✅ Create roles (super-admin, admin, client)
- ✅ Create permissions
- ✅ Assign permissions to roles
- ✅ Super admin has all permissions
- ✅ Client has limited permissions
- ✅ Check multiple roles
- ✅ Sync roles
- ✅ Full Spatie Permission integration

## 🎯 Test Features

### Database Management
- Uses SQLite in-memory database for speed
- `RefreshDatabase` trait for clean slate each test
- Automatic migration loading
- Spatie Permission migrations included

### Configuration
- Test-specific environment variables
- Isolated from main application
- Mock API keys for testing
- Bilingual support testing (EN/AR)

### Assertions Covered
- Database assertions (`assertDatabaseHas`)
- Model relationships
- Scopes and query builders
- Translatable field switching
- Livewire component state
- Role and permission checks
- Helper function outputs
- Seeder idempotency

## 🔧 Configuration Files

### phpunit.xml
```xml
<testsuites>
    <testsuite name="Unit">tests/Unit</testsuite>
    <testsuite name="Feature">tests/Feature</testsuite>
</testsuites>
```

### TestCase.php
Base test case that:
- Sets up Orchestra Testbench
- Configures SQLite in-memory database
- Loads package migrations
- Registers service providers
- Sets default config values

## 📝 Writing New Tests

### Unit Test Example
```php
namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\YourModel;

class YourModelTest extends TestCase
{
    /** @test */
    public function it_does_something()
    {
        $model = YourModel::create(['field' => 'value']);
        
        $this->assertDatabaseHas('your_table', ['field' => 'value']);
        $this->assertEquals('value', $model->field);
    }
}
```

### Feature Test Example
```php
namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Livewire\Livewire;
use Taba\Crm\Livewire\YourComponent;

class YourFeatureTest extends TestCase
{
    /** @test */
    public function it_performs_action()
    {
        $component = Livewire::test(YourComponent::class);
        
        $component->call('yourMethod')
            ->assertSet('property', 'expected_value');
    }
}
```

## 🐛 Troubleshooting

### Tests Not Found
```bash
composer dump-autoload
```

### Database Errors
Check that migrations are properly loaded in `TestCase.php`:
```php
protected function setUpDatabase()
{
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
}
```

### Permission Errors
Ensure Spatie Permission migrations run:
```php
include_once __DIR__ . '/../../../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
```

## 📈 Continuous Integration

### GitHub Actions Example
```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: composer test
```

## ✅ Test Checklist

Before releasing:
- [ ] All unit tests pass
- [ ] All feature tests pass
- [ ] Code coverage > 80%
- [ ] No database leaks between tests
- [ ] Translatable fields tested (EN/AR)
- [ ] Helper functions tested
- [ ] Seeders are idempotent
- [ ] Roles & permissions work correctly
- [ ] Livewire components render properly

## 🎓 Best Practices

1. **One Assertion Per Test**: Keep tests focused
2. **Descriptive Names**: Use `it_does_something` format
3. **Arrange-Act-Assert**: Structure tests clearly
4. **Clean Database**: Use `RefreshDatabase` trait
5. **Mock External APIs**: Don't hit real APIs in tests
6. **Test Edge Cases**: Empty values, nulls, invalid data
7. **Bilingual Testing**: Test both EN and AR locales

## 📚 Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [Orchestra Testbench](https://packages.tools/testbench)
- [Livewire Testing](https://livewire.laravel.com/docs/testing)
- [Spatie Permission Testing](https://spatie.be/docs/laravel-permission)

## 🤝 Contributing

When adding new features:
1. Write tests first (TDD)
2. Ensure tests pass
3. Update this README if needed
4. Run `composer test-coverage` to check coverage

---

**Total Tests**: 67 tests
**Coverage**: Models, Helpers, Seeders, Components, Roles
**Status**: ✅ All tests passing
