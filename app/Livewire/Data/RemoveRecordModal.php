<?php

namespace App\Livewire\Data;

use LivewireUI\Modal\ModalComponent;
use App\Models\Record;

class RemoveRecordModal extends ModalComponent
{
    public $record;
    public function mount($id)
    {
        $this->record = Record::find($id);
    }
    public function render()
    {
        return view('livewire.data.remove-record-modal');
    }

    public function deleteRecord()
    {
        $this->record->delete();
        $this->dispatch('reRenderRecordsList');
        $this->closeModal();
    }
}