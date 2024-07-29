<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptFromAssociationResource\Pages;
use App\Filament\Resources\ReceiptFromAssociationResource\RelationManagers;
use App\Models\ReceiptFromAssociation;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
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
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('association.name')
                    ->label('اسم الجمعية')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: False)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المندوب')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: False)
                    ->sortable(),
                Tables\Columns\TextColumn::make('factory.name')
                    ->label('اسم المصنع')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: False)
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('اسم السائق')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('transferToFactory.quantity')
                    ->label('الكمية المحولة')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: False)
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية المستلمة')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: False)
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time_of_collection')
                    ->dateTime()
                    ->label('وقت بدء الفحص')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time_of_collection')
                    ->dateTime()
                    ->label('وقت انتهاء الفحص')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_cleanliness')
                    ->label('نظافة العبوات')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return trans("filament.resources.receiptFromAssociation.$state");
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('transport_cleanliness')
                    ->label('نظافة وسيلة النقل')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return trans("filament.resources.receiptFromAssociation.$state");
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver_personal_hygiene')
                    ->label('النظافة الشخصية للسائق')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return trans("filament.resources.receiptFromAssociation.$state");
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('ac_operation')
                    ->label('تشغيل التكييف')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return trans("filament.resources.receiptFromAssociation.$state");
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->label('المندوب')
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'representative')->pluck('name', 'id');
                    })
                    ->relationship('user', 'name'),
                SelectFilter::make('factory_id')
                    ->label('المصنع')
                    ->multiple()
                    ->relationship('factory', 'name'),
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
