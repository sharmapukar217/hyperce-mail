<?php

namespace App\Providers;

use App\View\Components\CheckboxField;
use App\View\Components\FieldWrapper;
use App\View\Components\FileField;
use App\View\Components\Label;
use App\View\Components\SelectField;
use App\View\Components\SubmitButton;
use App\View\Components\TextareaField;
use App\View\Components\TextField;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component(TextField::class, 'hypercemail.text-field');
        Blade::component(TextareaField::class, 'hypercemail.textarea-field');
        Blade::component(FileField::class, 'hypercemail.file-field');
        Blade::component(SelectField::class, 'hypercemail.select-field');
        Blade::component(CheckboxField::class, 'hypercemail.checkbox-field');
        Blade::component(Label::class, 'hypercemail.label');
        Blade::component(SubmitButton::class, 'hypercemail.submit-button');
        Blade::component(FieldWrapper::class, 'hypercemail.field-wrapper');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
