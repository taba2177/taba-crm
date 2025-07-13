<div align="center">
<img src="https://raw.githubusercontent.com/taba-center/taba-logo/main/TABA-LOGO-2022-horizontal.png" alt="Taba CRM Logo" width="400">
<h1>Taba CRM Package for Laravel</h1>
<p>
A complete, "plug-and-play" CRM panel for Laravel, powered by Filament.
</p>
<p>
<a href="https://packagist.org/packages/taba/crm"><img src="https://img.shields.io/packagist/v/taba/crm.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://github.com/taba-center/crm/blob/main/LICENSE.md"><img src="https://img.shields.io/packagist/l/taba/crm.svg?style=flat-square" alt="License"></a>
<a href="https://packagist.org/packages/taba/crm"><img src="https://img.shields.io/packagist/dt/taba/crm.svg?style=flat-square" alt="Total Downloads"></a>
</p>
</div>

Taba CRM is a complete, reusable package that provides a full-featured CRM panel. It includes resources for managing posts, categories, and users, and comes pre-configured with essential plugins for a rich user experience.

✨ Features
Resource Management: Pre-built Filament resources for Posts, Categories, and Users.

Plugin Ecosystem: Integrated with popular plugins like Breezy (Profiles), Curator (Media), and Peek (Previews).

Simple Installation: Get up and running with a single custom Artisan command.

Customizable: Publishable assets (config, views, etc.) allow for easy customization.

📋 Prerequisites
Before you begin, ensure you have a fresh Laravel project with the following configured:

Laravel 10+

Filament 3+ installed (php artisan filament:install --panels)

Database connection set up in your .env file.

🚀 Installation
Getting started is simple. Follow these steps to integrate Taba CRM into your project.

Step 1: Require with Composer
First, pull the package into your project.

composer require taba/crm

Step 2: Run the Install Command
Next, run our custom installation command. This smart command handles all the necessary setup for the package and its dependencies, including publishing assets and running migrations.

php artisan crm:install

Note: This command will ask for your confirmation before running. It's a safe and transparent way to set up the required components.

Step 3: Register the Plugin
To activate the CRM panel, you need to register the CrmPlugin in your project's AdminPanelProvider.

Open app/Providers/Filament/AdminPanelProvider.php and add the plugin to the plugins() array:

use Taba\Crm\CrmPlugin; // 👈 Import the plugin at the top

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other panel settings
        ->plugins([
            new CrmPlugin(), // 👈 Add this line
        ]);
}

Step 4: Compile Frontend Assets
Finally, compile your project's frontend assets to ensure the admin panel's styles and scripts are loaded correctly.

npm install
npm run dev

And you're done! 🎉 You can now visit /admin and log in to access your new CRM panel.

🔧 Customization (Optional)
If you need to modify the package's default behavior, you can publish its assets.

Configuration: php artisan vendor:publish --tag=crm-config

Views: php artisan vendor:publish --tag=crm-views

Database Files: php artisan vendor:publish --tag=crm-database

📄 License
The Taba CRM is open-sourced software licensed under the MIT license.
