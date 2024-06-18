<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Actions\EditAction;
use App\Filament\Widgets\StoreOverview;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        // dd(auth()->user()->is_admin);
        return [
            Actions\CreateAction::make()->visible(auth()->user()->is_admin == 'administrator' || auth()->user()->is_admin == 'manager'),
            EditAction::make('change store')
                        ->visible(auth()->user()->is_admin == 'administrator' || auth()->user()->is_admin == 'manager')
                        ->label('Change Store Location')
                        ->modalHeading('Change your current store location')
                        ->record(auth()->user())
                        ->color(Color::Fuchsia)
                        ->form([
                            Select::make('store_id')->label('Store')->options(Store::pluck('name','id'))->searchable()
                        ])->action(function(array $data){
                            auth()->user()->update([
                                'store_id' => $data['store_id']
                            ]);
                        })
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
        ];
    }
}
