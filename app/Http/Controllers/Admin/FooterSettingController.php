<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterSettingController extends Controller
{
    public function index()
    {
        $footer = FooterSetting::first() ?: new FooterSetting();
        return view('admin.footer-settings.index', compact('footer'));
    }

    public function update(Request $request)
    {
        $footer = FooterSetting::first();
        if (!$footer) {
            $footer = new FooterSetting();
        }

        $data = $request->validate([
            'about_text' => 'nullable|string',
            'copyright_text' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
        ]);

        $footer->fill($data);
        $footer->save();

        return back()->with('success', 'Footer settings updated successfully!');
    }
}
