<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferToFactoryResource\Pages;
use App\Filament\Resources\TransferToFactoryResource\RelationManagers;
use App\Models\TransferToFactory;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class TransferToFactoryResource extends Resource
{
    protected static ?string $model = TransferToFactory::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'تحويل الحليب من الجمعية الى المصنع';
    protected static ?string $pluralLabel = 'تحويل الحليب من الجمعية الى المصنع';

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
                    Forms\Components\Select::make('driver_id')
                        ->relationship('driver', titleAttribute: 'name')
                        ->label('السائق')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Forms\Components\Select::make('factory_id')
                        ->relationship('factory', titleAttribute: 'name')
                        ->label('المصنع')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Forms\Components\TextInput::make('means_of_transportation')
                        ->required()
                        ->label('وسيلة النفل')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->label('الكمية')
                        ->numeric(),
                    Forms\Components\DateTimePicker::make('date_and_time')
                        ->label('الوقت والتاريخ')
                        ->required(),
                    Forms\Components\Toggle::make('status')
                        ->label('الحالة')
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
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('association.name')
                    ->numeric()
                    ->label('اسم الجمعية')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Driver.name')
                    ->numeric()
                    ->label('السائق')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Factory.name')
                    ->numeric()
                    ->label('المصنع')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('means_of_transportation')
                    ->label('وسيلة النقل')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->label('الكمية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_and_time')
                    ->dateTime()
                    ->label('الوقت والتاريخ')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('الحالة')
                    ->boolean(),
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
                    }),
                    SelectFilter::make('factory_id')
                    ->label('المصنع')
                    ->multiple()
                    ->relationship('factory', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTransferToFactories::route('/'),
            'create' => Pages\CreateTransferToFactory::route('/create'),
            'view' => Pages\ViewTransferToFactory::route('/{record}'),
            'edit' => Pages\EditTransferToFactory::route('/{record}/edit'),
        ];
    }
}
