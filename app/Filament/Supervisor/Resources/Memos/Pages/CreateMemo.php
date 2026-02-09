<?php

namespace App\Filament\Supervisor\Resources\Memos\Pages;

use App\Filament\Supervisor\Resources\Memos\MemoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMemo extends CreateRecord
{
    protected static string $resource = MemoResource::class;
}
