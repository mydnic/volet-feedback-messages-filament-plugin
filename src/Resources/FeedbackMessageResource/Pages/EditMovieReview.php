<?php

namespace Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources\FeedbackMessageResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Mydnic\VoletFeedbackMessagesFilamentPlugin\Resources\VoletFeedbackMessagesResource;

class EditMovieReview extends EditRecord
{
    protected static string $resource = VoletFeedbackMessagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
