<?php

namespace App\Filament\Chief\Resources\Memos\Pages;

use App\Filament\Chief\Resources\Memos\MemoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMemo extends CreateRecord
{
    protected static string $resource = MemoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
