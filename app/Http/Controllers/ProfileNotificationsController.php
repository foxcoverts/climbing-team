<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationSettingsRequest;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileNotificationsController extends Controller
{
    public function show(Request $request): View
    {
        $settings = $this->getNotificationSettings($request->user());

        Gate::authorize('view', $settings);

        return view('profile.notifications.show', [
            'settings' => $settings,
        ]);
    }

    public function update(UpdateNotificationSettingsRequest $request): RedirectResponse
    {
        $settings = $this->getNotificationSettings($request->user());

        Gate::authorize('update', $settings);

        $settings->fill($request->validated());
        $settings->save();

        return redirect()->route('profile.notifications.show')
            ->with('alert.info', __('Notification Settings saved.'));
    }

    public function destroy(Request $request)
    {
        $settings = $this->getNotificationSettings($request->user());

        Gate::authorize('delete', $settings);

        $settings->delete();

        return redirect()->route('profile.notifications.show')
            ->with('alert.info', __('Notification Settings cleared.'));
    }

    /**
     * Find the NotificationSettings for the given user.
     */
    protected function getNotificationSettings(User $user): NotificationSettings
    {
        return NotificationSettings::where([
            'notifiable_id' => null,
            'notifiable_type' => null,
            'user_id' => $user->id,
        ])->firstOr(function () use ($user): NotificationSettings {
            return $this->newNotificationSettings($user);
        });
    }

    protected function newNotificationSettings(User $user): NotificationSettings
    {
        $settings = new NotificationSettings;
        $settings->user()->associate($user);

        return $settings;
    }
}
