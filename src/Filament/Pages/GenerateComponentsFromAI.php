<?php

namespace Taba\Crm\Filament\Pages;

use Taba\Crm\Services\GenerateComponentService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class GenerateComponentsFromAI extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static string $view = 'crm::filament.pages.generate-components-from-a-i';
    protected static ?string $navigationGroup = 'AI Tools';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Generate Blade Components';

    public ?array $data = [];
    public ?array $generatedComponents = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Textarea::make('raw_code')
                    ->label('Raw Blade/HTML Code')
                    ->placeholder('Paste the full code block you want to convert into separate Blade components.')
                    ->rows(25)
                    ->required(),
            ]);
    }

    public function generate(): void
    {
        $data = $this->form->getState();
        $service = app(GenerateComponentService::class);
        $this->generatedComponents = null; // Clear previous results

        $result = $service->generate($data['raw_code']);

        if (!$result) {
            Notification::make()->danger()->title('Component Generation Failed')->body('The AI service could not process the code. Please check the logs.')->send();
            return;
        }

        $this->generatedComponents = $result;
        Notification::make()->success()->title('Components Generated Successfully!')->body('Review the generated files below and click "Save Components" to write them to disk.')->send();
    }

    public function saveComponents(): void
    {
        if (empty($this->generatedComponents)) {
            Notification::make()->warning()->title('No Components to Save')->body('Please generate components first.')->send();
            return;
        }

        $path = resource_path('views/components/homepage');
        File::ensureDirectoryExists($path);

        $savedFiles = [];
        foreach ($this->generatedComponents as $filename => $code) {
            File::put($path . '/' . $filename, $code);
            $savedFiles[] = $filename;
        }
        \Illuminate\Support\Facades\Process::run("npm run build");

        Notification::make()->success()->title('Components Saved!')->body('The following files have been saved: ' . implode(', ', $savedFiles))->send();

        $this->generatedComponents = null; // Clear after saving
    }
}