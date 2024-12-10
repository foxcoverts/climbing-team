<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateKeyTransferRequest;
use App\Models\Key;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransferKeyController extends Controller
{
    /**
     * Show the form for transferring the key to another holder.
     */
    public function edit(Request $request, Key $key): View
    {
        Gate::authorize('transfer', $key);

        return view('key.transfer', [
            'ajax' => $request->ajax(),
            'key' => $key,
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateKeyTransferRequest $request, Key $key): RedirectResponse
    {
        Gate::authorize('transfer', $key);

        $key->holder()->associate($request->holder_id);
        $key->save();

        if ($key->wasChanged('holder_id')) {
            return redirect()->route('key.index')
                ->with('alert', [
                    'message' => __('Key transferred to :name.', ['name' => $key->holder->name]),
                    '$dispatch' => 'key:updated',
                ]);
        } else {
            return redirect()->route('key.index');
        }
    }
}
