<?php

namespace App\View\Components;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Flash extends Component
{
    /**
     * The form's method.
     *
     * @var \Illuminate\Support\Collection
     */
    public $messages;

    /**
     * Create a new component instance.
     *
     * @param  array  $messages
     *
     * @return void
     */
    public function __construct($messages = [])
    {
        foreach(session()->get('errors', new ViewErrorBag)->all() as $error) {
            flash()->error($error);
        };

        $this->messages = session()
            ->get('flash_notification', collect())
            ->concat($messages);

        session()->forget('flash_notification');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.flash');
    }

    public function getBorderColor($message)
    {
        return [
            'success' => ' border-green-700 ',
            'danger' => ' border-red-700 ',
            'info' => ' border-blue-700 ',
            'warning' => ' border-yellow-800 ',
        ][$message->level];
    }

    public function getTitleColor($message)
    {
        return [
            'success' => ' text-green-700 ',
            'danger' => ' text-red-700 ',
            'info' => ' text-blue-700 ',
            'warning' => ' text-yellow-800 ',
        ][$message->level];
    }

    public function getTextColor($message)
    {
        return [
            'success' => ' text-green-900 ',
            'danger' => ' text-red-900 ',
            'info' => ' text-blue-900 ',
            'warning' => ' text-yellow-900 ',
        ][$message->level];
    }

    public function getTitle($message)
    {
        return $message->title ?? [
            'success' => 'Success!',
            'danger' => 'Error!',
            'info' => 'Information',
            'warning' => 'Warning!',
        ][$message->level];
    }

    public function getIcon($message)
    {
        return [
            'success' => 'check-circle',
            'danger' => 'x-circle',
            'info' => 'info',
            'warning' => 'alert-triangle',
        ][$message->level];
    }
}
