<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociationResource\RelationManagers\ActivitylogRelationManager;
use App\Filament\Resources\UserRepresentativeResource\Pages;
use App\Filament\Resources\UserRepresentativeResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRepresentativeResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'مندوب';
    protected static ?string $pluralLabel = "المناديب";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | array $routeMiddleware = [
        'auth:web',
        'Permission:institution',
    ];
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم المندوب')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('رقم الموبايل')
                    ->required()
                    ->unique('users', 'phone')
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
                Forms\Components\Select::make('factory_id')
                    ->relationship('factory', titleAttribute: 'name')
                    ->label('المصنع')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم المصنع')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Toggle::make('status')
                    ->default(1)
                    ->label('حالة المندوب')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المندوب')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الموبايل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('factory.name')
                    ->numeric()
                    ->label('اسم المصنع')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('حالة المندوب')
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
            ActivitylogRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
