<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class FileField extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $label = '')
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.file-field');
    }
}
