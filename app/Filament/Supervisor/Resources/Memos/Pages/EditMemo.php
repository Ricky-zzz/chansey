<?php

namespace App\Filament\Supervisor\Resources\Memos\Pages;

use App\Filament\Supervisor\Resources\Memos\MemoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMemo extends EditRecord
{
    protected static string $resource = MemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
