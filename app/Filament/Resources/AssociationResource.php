<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociationResource\Pages;
use App\Filament\Resources\AssociationResource\RelationManagers;
use App\Models\Association;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociationResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'جمعية';
    protected static ?string $pluralLabel = 'الجمعيات';
    protected static string | array $routeMiddleware = [
        'auth:web',
        'Permission:institution',
    ];
    public static function  associationForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('اسم الجمعية')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('phone')
                ->tel()
                ->label('رقم الموبايل')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->confirmed()
                ->label('الرمز')
                ->maxLength(255),
            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->required()
                ->label('تأكيد الرمز')
                ->maxLength(255),
            Forms\Components\Toggle::make('status')
                ->default(1)
                ->label('حالة الجمعية')
                ->required(),
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::associationForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الجمعية')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الموبايل')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('حالة الجمعية')
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
            'index' => Pages\ListAssociations::route('/'),
            'create' => Pages\CreateAssociation::route('/create'),
            'view' => Pages\ViewAssociation::route('/{record}'),
            'edit' => Pages\EditAssociation::route('/{record}/edit'),
        ];
    }
}
