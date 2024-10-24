<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationSettingsRequest;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingNotificationsController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): View
    {
        $settings = $this->getNotificationSettings($booking, $request->user());

        Gate::authorize('view', $settings);

        return view('booking.notifications.show', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationSettingsRequest $request, Booking $booking): RedirectResponse
    {
        $settings = $this->getNotificationSettings($booking, $request->user());

        Gate::authorize('update', $settings);

        $settings->fill($request->validated());
        $settings->save();

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Notifications saved.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        $settings = $this->newNotificationSettings($booking, $request->user());

        Gate::authorize('delete', $settings);

        $settings->delete();

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Notifications cleared.'));
    }

    /**
     * Find the NotificationSettings for the given booking & user pair.
     */
    protected function getNotificationSettings(Booking $booking, User $user): NotificationSettings
    {
        return NotificationSettings::where([
            'notifiable_id' => $booking->id,
            'notifiable_type' => $booking::class,
            'user_id' => $user->id,
        ])->firstOr(function () use ($booking, $user): NotificationSettings {
            return $this->newNotificationSettings($booking, $user);
        });
    }

    protected function newNotificationSettings(Booking $booking, User $user): NotificationSettings
    {
        $settings = new NotificationSettings;
        $settings->notifiable()->associate($booking);
        $settings->user()->associate($user);

        return $settings;
    }
}
