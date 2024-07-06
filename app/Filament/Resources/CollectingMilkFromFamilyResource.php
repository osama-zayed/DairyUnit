<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectingMilkFromFamilyResource\Pages;
use App\Filament\Resources\CollectingMilkFromFamilyResource\RelationManagers;
use App\Models\CollectingMilkFromFamily;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectingMilkFromFamilyResource extends Resource
{
    protected static ?string $model = CollectingMilkFromFamily::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'جمع الحليب من الاسر';
    protected static ?string $pluralLabel = 'الحليب المجمع من الاسر';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('association_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('associations_branche_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('family_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('collection_date_and_time')
                    ->required(),
                Forms\Components\TextInput::make('period')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('association_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('associations_branche_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('collection_date_and_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('وقت الاضافة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('وقت التعديل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('association_id')
                    ->label('الجمعية')
                    ->relationship('Association', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollectingMilkFromCaptivities::route('/'),
            'create' => Pages\CreateCollectingMilkFromFamily::route('/create'),
            'view' => Pages\ViewCollectingMilkFromFamily::route('/{record}'),
            'edit' => Pages\EditCollectingMilkFromFamily::route('/{record}/edit'),
        ];
    }
}
