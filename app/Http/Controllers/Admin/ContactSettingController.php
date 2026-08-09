<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    public function index()
    {
        $setting = ContactSetting::first() ?: new ContactSetting();
        return view('admin.contact-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = ContactSetting::first();
        if (!$setting) {
            $setting = new ContactSetting();
        }

        $data = $request->validate([
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'working_hours' => 'nullable|string|max:255',
            'map_url' => 'nullable|string',
        ]);

        $setting->fill($data);
        $setting->save();

        return back()->with('success', 'Contact settings updated successfully!');
    }
}
