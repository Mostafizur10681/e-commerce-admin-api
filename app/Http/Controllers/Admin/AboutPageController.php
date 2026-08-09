<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        $about = AboutPage::first() ?: new AboutPage();
        return view('admin.about.index', compact('about'));
    }

    public function update(Request $request)
    {
        $about = AboutPage::first();
        if (!$about) {
            $about = new AboutPage();
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($about->image) {
                $this->deleteImage($about->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'about');
        }

        $about->fill($data);
        $about->save();

        return back()->with('success', 'About page content updated successfully!');
    }
}
