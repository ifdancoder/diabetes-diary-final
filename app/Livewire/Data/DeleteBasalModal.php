<?php

namespace App\Livewire\Data;

use LivewireUI\Modal\ModalComponent;

use App\Models\Record;

class DeleteBasalModal extends ModalComponent
{
    public $record;
    public function mount($id)
    {
        $this->record = Record::find($id);
    }

    public function render()
    {
        return view('livewire.data.delete-basal-modal');
    }

    public function deleteBasal()
    {
        $this->record->basalValues()->delete();
        $this->dispatch('reRenderBasalList');
        $this->closeModal();
    }
}
