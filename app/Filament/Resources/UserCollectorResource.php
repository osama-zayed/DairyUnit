<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserCollectorResource\Pages;
use App\Filament\Resources\UserCollectorResource\RelationManagers;
use App\Filament\Resources\UserCollectorResource\RelationManagers\ActivitylogRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class UserCollectorResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'مجمع';
    protected static ?string $pluralLabel = "المجمعين";
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static string | array $routeMiddleware = [
        'auth:web',
        'Permission:institution',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([

                    Forms\Components\TextInput::make('name')
                        ->label('اسم المجمع')
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
                    Forms\Components\Toggle::make('status')
                        ->default(1)
                        ->label('حالة المجمع')
                        ->required(),
                ])->columns(2)->collapsed(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المجمع')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الموبايل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('association.name')
                    ->numeric()
                    ->searchable()
                    ->label('اسم الجمعية')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('حالة المجمع')
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
                    }),
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
            ActivitylogRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
