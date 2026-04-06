<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'pageTitle' => 'Company Settings',
            'settings' => CompanySetting::query()->firstOrCreate([], CompanySetting::defaults()),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = CompanySetting::query()->firstOrCreate([], CompanySetting::defaults());

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'support_phone' => ['required', 'string', 'max:255'],
            'support_location' => ['required', 'string', 'max:255'],
            'operating_hours' => ['required', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_logo') && $settings->logo_path) {
            Storage::disk('public')->delete($settings->logo_path);
            $validated['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        unset($validated['logo'], $validated['remove_logo']);

        $settings->update($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Company settings updated successfully.');
    }
}
