<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'test.resource_plural';
    protected static ?string $pluralModelLabel = 'test.resource_plural';
    protected static ?string $modelLabel = 'test.resource';

    public static function getNavigationLabel(): string
    {
        return __('test.resource_plural');
    }

    public static function getModelLabel(): string
    {
        return __('test.resource');
    }

    public static function getPluralModelLabel(): string
    {
        return __('test.resource_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                    ->schema([
                        Forms\Components\Section::make()
                            ->columnSpan(4)
                            ->schema([
                                Forms\Components\Section::make()->schema([
                                    FileUpload::make('thumbnail')
                                        ->label(__('test.thumbnail'))
                                        ->image()
                                        ->imagePreviewHeight('150')
                                        ->panelAspectRatio('16:9')
                                        ->directory('test-thumbnails'),

                                    Toggle::make('status')
                                        ->label(__('test.status')),
                                ]),
                                Forms\Components\Section::make(__('test.meta_section'))
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->relationship('meta')
                                            ->schema([
                                                TextInput::make('purpose')->label(__('test.purpose')),
                                                TextInput::make('target_age_group')->label(__('test.target_age_group')),
                                                TextInput::make('test_type')->label(__('test.test_type')),
                                                TextInput::make('approximate_duration')->label(__('test.approximate_duration')),
                                                TextInput::make('required_tools')->label(__('test.required_tools')),
                                                TextInput::make('analysis_method')->label(__('test.analysis_method')),
                                                TextInput::make('reliability_coefficient')->label(__('test.reliability_coefficient')),
                                                TextInput::make('validity')->label(__('test.validity')),
                                                TextInput::make('language_requirement')->label(__('test.language_requirement')),
                                                TextInput::make('iq_estimation_possibility')->label(__('test.iq_estimation_possibility')),
                                                Textarea::make('main_applications')->label(__('test.main_applications')),
                                                Textarea::make('strengths')->label(__('test.strengths')),
                                                Textarea::make('limitations')->label(__('test.limitations')),
                                                Textarea::make('advanced_versions')->label(__('test.advanced_versions')),
                                                Textarea::make('advantages_of_execution')->label(__('test.advantages_of_execution')),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make()
                            ->columnSpan(8)
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('test.title'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('slug')
                                    ->label(__('test.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->rules([
                                        'regex:/^[a-z0-9-]+$/',
                                    ]),

                                TiptapEditor::make('description')
                                    ->label(__('test.description'))
                                    ->profile('default')
                                    ->extraInputAttributes(['style' => 'min-height: 12rem;'])
                                    ->nullable(),
                            ]),
                    ]),

                // سکشن جدید برای متا


                Forms\Components\Section::make()
                    ->label(__('test.questions'))
                    ->schema([
                        Repeater::make('questions')
                            ->label(__('test.questions'))
                            ->relationship('questions')
                            ->schema([
                                Textarea::make('label')
                                    ->label(__('test.label'))
                                    ->required(),

                                Select::make('type')
                                    ->label(__('test.type'))
                                    ->options([
                                        'text' => __('test.type_text'),
                                        'textarea' => __('test.type_textarea'),
                                        'number' => __('test.type_number'),
                                        'date' => __('test.type_date'),
                                        'file' => __('test.type_file'),
                                    ])
                                    ->required(),

                                TextInput::make('order')
                                    ->label(__('test.order'))
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_required')
                                    ->label(__('test.is_required'))
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->minItems(1)
                            ->defaultItems(1)
                            ->orderColumn('order'),
                    ])
                    ->columnSpanFull(),
            ]);
    }





    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label(__('test.thumbnail'))
                    ->url(fn ($record) => asset('storage/' . $record->thumbnail))
                    ->height(50)
                    ->width(50)
                    ->circular(),
                TextColumn::make('title')
                    ->label(__('test.title'))
                    ->searchable(),

                TextColumn::make('description')
                    ->label(__('test.description'))
                    ->limit(30),

                ToggleColumn::make('status')
                    ->label(__('test.status')),

                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label(__('test.question_count')),

                TextColumn::make('created_at')
                    ->label(__('test.created_at'))
                    ->dateTime('Y/m/d'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'view' => Pages\ViewTest::route('/{record}'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}

