<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        $valueIds = [];
        $groupIds = [];
        foreach ($data as $key => $val) {
            if (str_starts_with($key, 'filter_values_') && is_array($val)) {
                $valueIds = array_merge($valueIds, $val);
                if (!empty($val)) {
                    $groupIds[] = (int) str_replace('filter_values_', '', $key);
                }
            }
        }

        $record->filterValues()->sync($valueIds);
        $record->filterGroups()->sync($groupIds);
    }
}
