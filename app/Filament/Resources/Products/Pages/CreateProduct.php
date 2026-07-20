<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        $valueIds = [];
        foreach ($data as $key => $val) {
            if (str_starts_with($key, 'filter_group_') && is_array($val)) {
                $valueIds = array_merge($valueIds, $val);
            }
        }

        $record->filterValues()->sync($valueIds);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
