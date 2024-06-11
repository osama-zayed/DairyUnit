<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociationsBranchResource\Pages;
use App\Filament\Resources\AssociationsBranchResource\RelationManagers;
use App\Models\AssociationsBranch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociationsBranchResource extends Resource
{
    protected static ?string $model = AssociationsBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'فرع الجمعية';
    protected static ?string $pluralLabel = 'فروع الجمعيات';
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
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                        ->label('اسم الجمعية')
                        ->required()
                        ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->label('اسم الفرع')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الفرع')
                    ->searchable(),
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
                //
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
            'index' => Pages\ListAssociationsBranches::route('/'),
            'create' => Pages\CreateAssociationsBranch::route('/create'),
            'view' => Pages\ViewAssociationsBranch::route('/{record}'),
            'edit' => Pages\EditAssociationsBranch::route('/{record}/edit'),
        ];
    }
}
