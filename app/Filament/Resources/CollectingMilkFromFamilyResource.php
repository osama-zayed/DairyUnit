<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectingMilkFromFamilyResource\Pages;
use App\Filament\Resources\CollectingMilkFromFamilyResource\RelationManagers;
use App\Models\CollectingMilkFromFamily;
use App\Models\User;
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

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'تجميع الحليب من الاسر';
    protected static ?string $pluralLabel = 'الحليب المجمع من الاسر';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('association_id')
                    ->relationship('association', titleAttribute: 'name')
                    ->label('الجمعية')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->options(function () {
                        return User::where('user_type', 'association')->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', titleAttribute: 'name')
                    ->label('فرع الجمعية')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->options(function () {
                        return User::where('user_type', 'collector')->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('family_id')
                    ->relationship('family', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->label('اسم الاسرة')
                    ->live(),
                Forms\Components\DateTimePicker::make('collection_date_and_time')
                    ->label('وقت التجميع')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->label('الكمية')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('association.name')
                    ->numeric()
                    ->label('اسم الجمعية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('فرع الجمعية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.name')
                    ->numeric()
                    ->label('اسم الاسرة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('collection_date_and_time')
                    ->dateTime()
                    ->label('وقت التجميع')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->label('الكمية')
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
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'association')->pluck('name', 'id');
                    })
                    ->relationship('association', 'name'),
                SelectFilter::make('user_id')
                    ->label('فرع الجمعية')
                    ->options(function () {
                        return User::where('user_type', 'collector')->pluck('name', 'id');
                    })
                    ->relationship('user', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //      Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'view' => Pages\ViewCollectingMilkFromFamily::route('/{record}'),
            // 'edit' => Pages\EditCollectingMilkFromFamily::route('/{record}/edit'),
        ];
    }
}
