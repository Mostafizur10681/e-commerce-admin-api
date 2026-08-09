@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Attributes', 'url' => route('admin.attributes.index')],
            ['label' => 'Add Attribute']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Add New Attribute</h1>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.attributes.store') }}" class="space-y-4" x-data="{
            tagInput: '',
            values: ['S', 'M', 'L', 'XL'],
            addTag() {
                if (this.tagInput.trim() && !this.values.includes(this.tagInput.trim())) {
                    this.values.push(this.tagInput.trim());
                    this.tagInput = '';
                }
            },
            removeTag(idx) {
                this.values.splice(idx, 1);
            }
        }">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Attribute Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Size, Color, Fabric" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Attribute Type *</label>
                <select name="type" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    <option value="select">Select / Dropdown</option>
                    <option value="radio">Radio Buttons</option>
                    <option value="button">Button Pills</option>
                    <option value="color">Color Swatch</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Values *</label>
                <div class="flex gap-2 mb-2">
                    <input 
                        type="text" 
                        x-model="tagInput" 
                        @keydown.enter.prevent="addTag()" 
                        placeholder="Type value (e.g. XL) and click Add" 
                        class="flex-1 px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                    >
                    <button type="button" @click="addTag()" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-xs font-bold rounded-xl">
                        Add Value
                    </button>
                </div>

                <!-- Hidden inputs for form submit -->
                <template x-for="(val, idx) in values" :key="idx">
                    <input type="hidden" name="values[]" :value="val">
                </template>

                <!-- Value tags display -->
                <div class="flex flex-wrap gap-2 p-3 bg-gray-50 dark:bg-gray-950/50 border border-gray-200 dark:border-gray-800 rounded-xl min-h-[50px]">
                    <template x-for="(val, idx) in values" :key="idx">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold text-gray-800 dark:text-gray-200 shadow-sm">
                            <span x-text="val"></span>
                            <button type="button" @click="removeTag(idx)" class="text-gray-400 hover:text-rose-500 font-bold">&times;</button>
                        </span>
                    </template>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('admin.attributes.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20">Save Attribute</button>
            </div>
        </form>
    </div>

</div>
@endsection
