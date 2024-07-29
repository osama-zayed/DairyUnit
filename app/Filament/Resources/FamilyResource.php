<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Filament\Resources\FamilyResource\RelationManagers;
use App\Models\Family;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'اسرة منتجة';
    protected static ?string $pluralLabel = "الاسر المنتجة";
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('اسم الاسرة')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->label('رقم الهاتف')
                    ->maxLength(255),
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
                Forms\Components\Select::make('associations_branche_id')
                    ->relationship('associationsBranche', titleAttribute: 'name')
                    ->label('فرع الجمعية')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->options(function () {
                        return User::where('user_type', 'collector')->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->default(1)
                    ->label('حالة الاسرة')
                    ->required(),
                    ])->columns(2)->collapsed(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الاسره')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('association.name')
                    ->numeric()
                    ->searchable()
                    ->label('اسم الجمعية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('associationsBranche.name')
                    ->numeric()
                    ->label('اسم الفرع')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('حالة الاسرة')
                    ->boolean()
                    ->action(function ($record, $column) {
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
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
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFamilies::route('/'),
            // 'create' => Pages\CreateFamily::route('/create'),
            'view' => Pages\ViewFamily::route('/{record}'),
            // 'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }
}
