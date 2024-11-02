<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Filament\Resources\FamilyResource\RelationManagers;
use App\Models\Directorate;
use App\Models\Family;
use App\Models\Governorate;
use App\Models\Isolation;
use App\Models\User;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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


                    Forms\Components\TextInput::make('local_cows_producing')
                        ->label('الأبقار المحلية المنتجة')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('local_cows_non_producing')
                        ->label('الأبقار المحلية غير المنتجة')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('born_cows_producing')
                        ->label('الأبقار المولودة المنتجة')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('born_cows_non_producing')
                        ->label('الأبقار المولودة غير المنتجة')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('imported_cows_producing')
                        ->label('الأبقار المستوردة المنتجة')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('imported_cows_non_producing')
                        ->label('الأبقار المستوردة غير المنتجة')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('governorate_id')
                        ->relationship('governorate', titleAttribute: 'name')
                        ->label('المحافظة')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options(function () {
                            return Governorate::pluck('name', 'id'); // تأكد من وجود موديل Governorate
                        })
                        ->required(),
                    Forms\Components\Select::make('directorate_id')
                        ->relationship('directorate', titleAttribute: 'name')
                        ->label('المديرية')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options(function () {
                            return Directorate::pluck('name', 'id'); // تأكد من وجود موديل Directorate
                        })
                        ->required(),
                    Forms\Components\Select::make('isolation_id')
                        ->relationship('isolation', titleAttribute: 'name')
                        ->label('العزلة')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options(function () {
                            return Isolation::pluck('name', 'id'); // تأكد من وجود موديل Isolation
                        })
                        ->required(),
                    Forms\Components\Select::make('village_id')
                        ->relationship('village', titleAttribute: 'name')
                        ->label('القرية')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options(function () {
                            return Village::pluck('name', 'id'); // تأكد من وجود موديل Village
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
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الاسره')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('association.name')
                    ->label('اسم الجمعية')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('associationsBranche.name')
                    ->label('اسم الفرع')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('governorate.name')
                    ->label('اسم المحافظة')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('directorate.name')
                    ->label('اسم المديرية')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('isolation.name')
                    ->label('اسم العزلة')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('village.name')
                    ->label('اسم القرية')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('local_cows_producing') // عدد الأبقار المحلية المنتجة
                    ->label('الأبقار المحلية المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('local_cows_non_producing') // عدد الأبقار المحلية غير المنتجة
                    ->label('الأبقار المحلية غير المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('born_cows_producing') // عدد الأبقار المولودة المنتجة
                    ->label('الأبقار المولودة المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('born_cows_non_producing') // عدد الأبقار المولودة غير المنتجة
                    ->label('الأبقار المولودة غير المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('imported_cows_producing') // عدد الأبقار المستوردة المنتجة
                    ->label('الأبقار المستوردة المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('imported_cows_non_producing') // عدد الأبقار المستوردة غير المنتجة
                    ->label('الأبقار المستوردة غير المنتجة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    }),
                SelectFilter::make('associations_branche_id')
                    ->multiple()
                    ->label('فرع الجمعية')
                    ->options(function () {
                        return User::where('user_type', 'collector')->pluck('name', 'id');
                    }),
                SelectFilter::make('governorate_id') // فلتر المحافظة
                    ->label('المحافظة')
                    ->multiple()
                    ->options(function () {
                        return Governorate::pluck('name', 'id'); // تأكد من وجود موديل Governorate
                    }),
                SelectFilter::make('directorate_id') // فلتر المديرية
                    ->label('المديرية')
                    ->multiple()
                    ->options(function () {
                        return Directorate::pluck('name', 'id'); // تأكد من وجود موديل Directorate
                    }),
                SelectFilter::make('isolation_id') // فلتر العزلة
                    ->label('العزلة')
                    ->multiple()
                    ->options(function () {
                        return Isolation::pluck('name', 'id'); // تأكد من وجود موديل Isolation
                    }),
                SelectFilter::make('village_id') // فلتر القرية
                    ->label('القرية')
                    ->multiple()
                    ->options(function () {
                        return Village::pluck('name', 'id'); // تأكد من وجود موديل Village
                    }),
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
