<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class TextField extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var string */
    public $type;

    /** @var null */
    public $value;

    /**
     * Create the component instance.
     *
     * @param  null  $value
     */
    public function __construct(string $name, string $label = '', string $type = 'text', $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.text-field');
    }
}
