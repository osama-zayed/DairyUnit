<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptFromAssociationResource\Pages;
use App\Filament\Resources\ReceiptFromAssociationResource\RelationManagers;
use App\Models\ReceiptFromAssociation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiptFromAssociationResource extends Resource
{
    protected static ?string $model = ReceiptFromAssociation::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-circle';


    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 9;
    protected static ?string $modelLabel = 'استلام الحليب من الجمعية الى المصنع';
    protected static ?string $pluralLabel = 'استلام الحليب من الجمعية الى المصنع';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReceiptFromAssociations::route('/'),
            // 'create' => Pages\CreateReceiptFromAssociation::route('/create'),
            'view' => Pages\ViewReceiptFromAssociation::route('/{record}'),
            // 'edit' => Pages\EditReceiptFromAssociation::route('/{record}/edit'),
        ];
    }    
}
