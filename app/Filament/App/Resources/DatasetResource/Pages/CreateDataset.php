<?php

namespace App\Filament\App\Resources\DatasetResource\Pages;

use App\Events\AfterDatasetCreated;
use App\Filament\App\Resources\DatasetResource;
use App\Models\Dataset;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDataset extends CreateRecord
{
    protected static string $resource = DatasetResource::class;

//    change tenant filter to user filter
    protected function handleRecordCreation(array $data): Model
    {
        /** @var Dataset $record */
        $record = new ($this->getModel())($data);
        $record->user()->associate(auth()->user());
        $record->save();
        $record->members()->attach(auth()->user()); // populate dataset_user too
        $record->save();
        AfterDatasetCreated::dispatch();
        return $record;
    }
}
