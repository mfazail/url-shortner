<?php

namespace App\Filament\Profile\Resources;

use App\Filament\Profile\Resources\UrlResource\Pages;
use App\Filament\Profile\Resources\UrlResource\Widgets\UrlMonthsOverview;
use App\Models\Url;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UrlResource extends Resource
{
    protected static ?string $model = Url::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->url(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->tooltip(fn (Url $record): string => $record->url)
                    ->limit(30),
                Tables\Columns\TextColumn::make('short_url')
                    ->copyable()
                    ->copyableState(fn (string $state): string => env('APP_URL', 'http://localhost:8000') . '/' . $state)
                    ->prefix(env('APP_URL', 'http://localhost:8000') . '/')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        Forms\Components\TextInput::make('url')
                            ->label("Original Url")
                            ->required()
                            ->url(),
                        Forms\Components\TextInput::make('short_url')
                            ->prefix(env('APP_URL', 'http://localhost:8000') . '/')
                            ->label('Make custom short url')
                            ->helperText('Only alphanumeric characters, dashes, and underscores are allowed.')
                            ->alphaDash()
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUrls::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    
}
