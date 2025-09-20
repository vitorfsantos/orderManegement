<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Appearance extends Component
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('livewire.settings.appearance')->layout('components.layouts.app');
  }
}
