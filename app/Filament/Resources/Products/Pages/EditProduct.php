<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $galleryImages = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract gallery images before saving (not a real DB column)
        $this->galleryImages = $data['gallery_images'] ?? [];
        unset($data['gallery_images']);
        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        // Sync filter values
        $valueIds = [];
        foreach ($data as $key => $val) {
            if (str_starts_with($key, 'filter_group_') && is_array($val)) {
                $valueIds = array_merge($valueIds, $val);
            }
        }
        $record->filterValues()->sync($valueIds);

        // Sync gallery images (delete old, insert new)
        $record->images()->delete();
        if (is_array($this->galleryImages)) {
            foreach ($this->galleryImages as $index => $path) {
                if ($path) {
                    $record->images()->create([
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
