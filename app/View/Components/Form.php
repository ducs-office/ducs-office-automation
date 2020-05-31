<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * The form's method.
     *
     * @var string
     */
    public $method;

    /**
     * The action executed by the form.
     *
     * @var string
     */
    public $action;

    /**
     * Indicates if the form supports file uploads.
     *
     * @var bool
     */
    public $hasFiles;

    /**
     * Create a new component instance.
     *
     * @param  string  $method
     * @param  string  $action
     * @param  bool  $hasFiles
     *
     * @return void
     */
    public function __construct($method, $action, $hasFiles = false)
    {
        $this->method = strtoupper($method);
        $this->action = $action;
        $this->hasFiles = $hasFiles;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return <<<'HTML'
            <form method="{{ $method !== 'GET' ? 'POST' : $method }}" action="{{ $action }}" {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!} {{ $attributes }}>
                @if ($method !== 'GET' && $method !== 'POST')
                @method($method)
                @endif
                @csrf
                {{ $slot }}
            </form>
        HTML;
    }
}
