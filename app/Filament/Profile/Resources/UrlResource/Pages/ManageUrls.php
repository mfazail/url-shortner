<?php

namespace App\Filament\Profile\Resources\UrlResource\Pages;

use App\Filament\Profile\Resources\UrlResource;
use App\Filament\Profile\Resources\UrlResource\Widgets\UrlMonthsOverview;
use App\Models\Url;
use Filament\Actions\CreateAction;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageUrls extends ManageRecords
{
    protected static string $resource = UrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    // put current user id
                    $data['user_id'] = auth()->id();
                    // generate short url 
                    $data['short_url'] = Str::lower(Str::substr(Str::ulid(), 0, 15));

                    return $data;
                })
                ->modalWidth(MaxWidth::Large)
                ->successNotification(
                    fn (Url $record) =>
                    Notification::make()
                        ->success()
                        ->duration(5000)
                        ->title('Url created')
                        ->body('The url has been created successfully.')
                        ->actions([
                            Action::make('Copy short url')
                                ->view('filament-notifications::actions.copy')
                                ->button()
                                ->dispatch('copy-to-clipboard', [
                                    'value' => $record->short_url,
                                ])
                        ])
                )
        ];
    }
}
