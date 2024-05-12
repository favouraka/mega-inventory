<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use App\Models\User;

class Settings extends Page implements HasForms,  HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Form $form): Form
    {
        //
        return $form->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required(),
                       
                ])->columns(2)->statePath('data');
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label('Save')
            ->requiresConfirmation()
            ->action(fn () => auth()->user()->update());
    }
}
