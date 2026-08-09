<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use UploadImageTrait;

    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%")->orWhere('website', 'like', "%{$s}%");
        }

        $partners = $query->latest()->paginate(15)->withQueryString();

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'partners');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully!');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                $this->deleteImage($partner->logo);
            }
            $data['logo'] = $this->uploadImage($request->file('logo'), 'partners');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully!');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        if ($partner->logo) {
            $this->deleteImage($partner->logo);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully!');
    }
}
