<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        if (request()->isAdmin()) {
            return view('admin.layouts.guest');
        }

        return view('front.layouts.guest');
    }
}
