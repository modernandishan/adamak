<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Filament\Resources\FamilyResource\RelationManagers;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'family.resource';
    protected static ?string $pluralModelLabel = 'family.resource_plural';
    protected static ?string $modelLabel = 'family.resource';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('family.title'))
                            ->required()
                            ->columnSpan(1),

                        TiptapEditor::make('description')
                            ->label(__('family.description'))
                            ->profile('minimal'),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Repeater::make('members')
                            ->label(__('family.members'))
                            ->relationship('members')
                            ->minItems(1) // ← حتماً باید حداقل یک عضو وارد شود
                            ->schema([
                                TextInput::make('full_name')->label(__('family.full_name'))->required(),
                                Select::make('relation')
                                    ->label(__('family.relation'))
                                    ->options([
                                        1 => __('family.father'),
                                        2 => __('family.mother'),
                                        3 => __('family.son'),
                                        4 => __('family.daughter'),
                                    ])
                                    ->required(),
                                Select::make('gender')
                                    ->label(__('family.gender'))
                                    ->options([
                                        1 => __('family.male'),
                                        2 => __('family.female'),
                                    ])
                                    ->required(),
                                app()->getLocale() === 'fa'
                                    ? DatePicker::make('birth_date')->jalali()->label(__('family.birth_date'))
                                    : DatePicker::make('birth_date')->label(__('family.birth_date')),
                            ]),
                    ]),


            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label(__('family.creator'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('family.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label(__('family.members'))
                    ->counts('members'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
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
            'create' => Pages\CreateFamily::route('/create'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('family.resource_plural');
    }

    public static function getModelLabel(): string
    {
        return __('family.resource');
    }

    public static function getPluralModelLabel(): string
    {
        return __('family.resource_plural');
    }
}
