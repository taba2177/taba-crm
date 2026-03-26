<?php

// ⚠️  HELD MIGRATION — DO NOT RUN IN CI/CD OR AUTOMATED DEPLOY
//
// Purpose: Drops the legacy `homepage_sections` table which has been
//          superseded by `section_component` column on `post_categories`.
//
// When to run: Only during a planned maintenance window, after confirming:
//   1. No code references HomepageSection model
//   2. All existing homepage_sections data has been migrated to PostCategory.section_component
//   3. A full DB backup has been taken
//
// To run manually: php artisan migrate --path=packages/taba/crm/src/database/migrations/2026_03_12_000002_drop_homepage_sections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('homepage_sections');
    }

    public function down(): void
    {
        // Intentionally empty — restoration requires a backup restore.
        // The homepage_sections table has been superseded and should not be recreated automatically.
    }
};
