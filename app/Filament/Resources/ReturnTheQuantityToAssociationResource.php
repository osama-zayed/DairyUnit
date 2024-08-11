<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnTheQuantityToAssociationResource\Pages;
use App\Filament\Resources\ReturnTheQuantityToAssociationResource\RelationManagers;
use App\Models\ReturnTheQuantity;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReturnTheQuantityToAssociationResource extends Resource
{
    protected static ?string $model = ReturnTheQuantity::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'مردود الى الجمعيات';
    protected static ?string $pluralLabel = 'مردود الى الجمعيات';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([

                    Forms\Components\Select::make('association_id')
                        ->relationship('association', titleAttribute: 'name')
                        ->label('الجمعية المردود لها')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('user_id')
                        ->relationship('user', titleAttribute: 'name')
                        ->label('المندوب')
                        ->required(),

                    Forms\Components\Select::make('factory_id')
                        ->relationship('factory', titleAttribute: 'name')
                        ->label('المصنع')
                        ->required(),
                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('الوقت والتاريخ')
                        ->required(),
                    Forms\Components\TextInput::make('defective_quantity_due_to_coagulation')
                        ->label('الكمية التالفة بسبب التخثر')
                        ->numeric(),
                    Forms\Components\TextInput::make('defective_quantity_due_to_impurities')
                        ->label('الكمية التالفة بسبب الشوائب')
                        ->numeric(),
                    Forms\Components\TextInput::make('defective_quantity_due_to_density')
                        ->label('الكمية التالفة بسبب الكثافة')
                        ->numeric(),
                    Forms\Components\TextInput::make('defective_quantity_due_to_acidity')
                        ->label('الكمية التالفة بسبب الحموضة')
                        ->numeric(),

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
                    ->label('اسم الجمعية المردود لها')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('اسم المندوب')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('factory.name')
                    ->numeric()
                    ->label('اسم المصنع')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('defective_quantity_due_to_coagulation')
                    ->label('الكمية التالفة بسبب التخثر')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('defective_quantity_due_to_impurities')
                    ->label('الكمية التالفة بسبب الشوائب')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('defective_quantity_due_to_density')
                    ->label('الكمية التالفة بسبب الكثافة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('defective_quantity_due_to_acidity')
                    ->label('الكمية التالفة بسبب الحموضة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('اجمالي التالف')
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
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'association')->pluck('name', 'id');
                    }),
                SelectFilter::make('factory_id')
                    ->label('المصنع')
                    ->multiple()
                    ->relationship('factory', 'name'),
                SelectFilter::make('user_id')
                    ->label('المندوب')
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'representative')->pluck('name', 'id');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('print_pdf')
                    ->label('طباعة ك PDF')
                    ->action(function ($records) {
                        $recordIds = $records->pluck('id')->toArray();
                        return redirect()->route('ReturnTheQuantityToAssociation', ['data' => $recordIds]);
                    })
                    ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
               
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
            'index' => Pages\ListReturnTheQuantities::route('/'),
            // 'create' => Pages\CreateReturnTheQuantity::route('/create'),
            'view' => Pages\ViewReturnTheQuantity::route('/{record}'),
            // 'edit' => Pages\EditReturnTheQuantity::route('/{record}/edit'),
        ];
    }
}
