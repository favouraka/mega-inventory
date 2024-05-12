<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProductsInStore;
use App\Filament\Widgets\StoreOverview;
use App\Filament\Widgets\UsersInStore;
use App\Models\Store as ModelsStore;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;

class Store extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Store';

    protected static string $view = 'filament.pages.store';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make('change store')
                        ->visible(auth()->user()->is_admin)
                        ->label('Change Store Location')
                        ->modalHeading('Change your curret store location')
                        ->record(auth()->user())
                        ->color(Color::Fuchsia)
                        ->form([
                            Select::make('store_id')->label('Store')->options(ModelsStore::pluck('name','id'))->searchable()
                        ])->action(function(array $data){
                            auth()->user()->update([
                                'store_id' => $data['store_id']
                            ]);
                        }),
        ];
    }

    /**
     * Get the header widgets for the page.
     *
     * @return array
     */
    public function getHeaderWidgets(): array
    {
        return [
            StoreOverview::class,
            ProductsInStore::class,
            UsersInStore::class,
        ];
    }

    
}
