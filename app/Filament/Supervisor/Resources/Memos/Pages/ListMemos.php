<?php

namespace App\Filament\Supervisor\Resources\Memos\Pages;

use App\Filament\Supervisor\Resources\Memos\MemoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemos extends ListRecords
{
    protected static string $resource = MemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
