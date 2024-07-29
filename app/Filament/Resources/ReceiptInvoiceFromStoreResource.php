<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptInvoiceFromStoreResource\Pages;
use App\Filament\Resources\ReceiptInvoiceFromStoreResource\RelationManagers;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class ReceiptInvoiceFromStoreResource extends Resource
{
    protected static ?string $model = ReceiptInvoiceFromStore::class;


    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'توريد الحليب من المجمعين الى الجمعية';
    protected static ?string $pluralLabel = 'توريد الحليب من المجمعين الى الجمعية';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([

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
                    ->relationship('associationsBranche', titleAttribute: 'name')
                    ->label('فرع الجمعية')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->options(function () {
                        return User::where('user_type', 'collector')->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->label('الكمية')
                    ->numeric(),
                    Forms\Components\DateTimePicker::make('date_and_time')
                    ->label('الوقت والتاريخ')
                    ->required(),
                    Forms\Components\Textarea::make('notes')
                    ->label('الملاحظات')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                    ])->columns(2)->collapsed(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('association.name')
                    ->numeric()
                    ->label('اسم الجمعية')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('associationsBranche.name')
                    ->numeric()
                    ->label('فرع الجمعية')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_and_time')
                    ->dateTime()
                    ->label('الوقت والتاريخ')
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
                    ->relationship('associationsBranche', 'name')
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
            'index' => Pages\ListReceiptInvoiceFromStores::route('/'),
            // 'create' => Pages\CreateReceiptInvoiceFromStore::route('/create'),
            'view' => Pages\ViewReceiptInvoiceFromStore::route('/{record}'),
            // 'edit' => Pages\EditReceiptInvoiceFromStore::route('/{record}/edit'),
        ];
    }
}
