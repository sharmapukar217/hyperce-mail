<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectField extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var array|Collection */
    public $options;

    /** @var null */
    public $value;

    /** @var bool */
    public $multiple;

    /**
     * Create the component instance.
     *
     * @param  array  $options
     * @param  null  $value
     */
    public function __construct(string $name, string $label = '', $options = [], $value = null, bool $multiple = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->value = $value;
        $this->multiple = $multiple;
    }

    public function isSelected($key): bool
    {
        if ($this->multiple) {
            if ($this->value instanceof Collection) {
                return $this->value->has($key);
            } elseif (is_array($this->value)) {
                return array_key_exists($key, $this->value);
            }
        }

        return $key == $this->value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.select-field');
    }
}
