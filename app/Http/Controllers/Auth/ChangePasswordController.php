<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function edit(): View
    {
        return view('profile.password.edit');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password'=> Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', __('Your password was updated successfully!'));
    }
}