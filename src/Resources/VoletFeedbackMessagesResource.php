<?php

namespace Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Mydnic\Volet\Features\FeatureManager;
use Mydnic\Volet\Models\FeedbackMessage;
use Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources\FeedbackMessageResource\Pages\CreateMovieReview;
use Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources\FeedbackMessageResource\Pages\EditMovieReview;
use Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources\FeedbackMessageResource\Pages\ListMovieReviews;

class VoletFeedbackMessagesResource extends Resource
{
    protected static ?string $model = FeedbackMessage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $modelLabel = 'Feedback Messages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->options(
                        collect(
                            app(FeatureManager::class)->getFeature('feedback-messages')
                                ->getCategories()
                        )
                            ->mapWithKeys(fn ($category) => [$category['slug'] => $category['name']])
                    )
                    ->required(),
                Textarea::make('message'),
                KeyValue::make('user_info')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'resolved' => 'Resolved',
                    ])
                    ->required(),
                DatePicker::make('created_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->sortable(),
                TextColumn::make('message'),
                TextColumn::make('status')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(
                        collect(
                            app(FeatureManager::class)->getFeature('feedback-messages')
                                ->getCategories()
                        )
                            ->mapWithKeys(fn ($category) => [$category['slug'] => $category['name']])
                    ),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'resolved' => 'Resolved',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('Mark As Read')
                        ->action(fn (FeedbackMessage $record) => $record->markAsRead())
                        ->icon('heroicon-m-eye'),
                    Action::make('Mark As Resolved')
                        ->action(fn (FeedbackMessage $record) => $record->markAsResolved())
                        ->icon('heroicon-m-check'),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListMovieReviews::route('/'),
            'create' => CreateMovieReview::route('/create'),
            'edit' => EditMovieReview::route('/{record}/edit'),
        ];
    }
}
