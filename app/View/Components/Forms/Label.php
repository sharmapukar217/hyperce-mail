<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Label extends Component
{
    /** @var string */
    public $name;

    /**
     * Create the component instance.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.label');
    }
}
