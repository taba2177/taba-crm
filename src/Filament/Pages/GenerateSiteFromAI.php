<?php

namespace Taba\Crm\Filament\Pages;

use Taba\Crm\Services\GenerateSeederService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class GenerateSiteFromAI extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;


    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static string $view = 'crm::filament.pages.generate-site-from-a-i';
    protected static ?string $navigationGroup = 'AI Tools';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public bool $seederFileExists = false;
    public ?string $seederOutput = null;

    public function mount(): void
    {
        $this->form->fill();
        $this->checkIfSeederExists();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Textarea::make('content')
                    ->label('Unstructured Website Content')
                    ->placeholder('Describe the pages, sections, and content for your website in plain text. The AI will convert this into a complete seeder file.')
                    ->rows(20)
                    ->required(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Seeder')
                ->icon('heroicon-o-sparkles')
                ->action('generate'),

            Action::make('runSeeder')
                ->label('Run Seeder')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Run AI Seeder')
                ->modalDescription('This will execute the seeder and will modify your database. Are you sure you want to continue?')
                ->action('runSeeder')
                ->visible($this->seederFileExists), // Only show if the seeder file exists
        ];
    }

    public function generate(): void
    {
        $data = $this->form->getState();
        $service = app(GenerateSeederService::class);
        $this->seederOutput = null; // Clear previous output on new generation

        $seederCode = $service->generate($data['content']);

        if (!$seederCode) {
            Notification::make()->danger()->title('Seeder Generation Failed')->body('The AI service could not generate the seeder. Check logs.')->send();
            return;
        }

        $fullSeederContent = "<?php\n\nnamespace Database\Seeders;\n\n" . $seederCode;
        $seederPath = database_path('seeders/AISiteSeeder.php');

        File::put($seederPath, $fullSeederContent);
        $this->checkIfSeederExists(); // Update button visibility

        Notification::make()->success()->title('Seeder Generated Successfully!')->body('The AISiteSeeder.php file has been created. You can now run it.')->send();
    }

    public function runSeeder(): void
    {
        try {
            // Execute the Artisan command
            Artisan::call('migrate:fresh --seed');
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\AISiteSeeder']);

            // Capture and display the full output
            $output = Artisan::output();
            $this->seederOutput = $output ?: 'Seeder executed successfully, but produced no output.';

            Notification::make()->success()->title('Seeder Executed Successfully!')->send();
        } catch (\Throwable $e) {
            // If an exception occurs, display the error message and stack trace
            $this->seederOutput = "An error occurred during execution:\n\n" . $e->getMessage() . "\n\n" . $e->getTraceAsString();
            Notification::make()->danger()->title('Seeder Execution Failed')->body('See output below for details.')->send();
        }
    }

    protected function checkIfSeederExists(): void
    {
        $this->seederFileExists = File::exists(database_path('seeders/AISiteSeeder.php'));
    }
}
