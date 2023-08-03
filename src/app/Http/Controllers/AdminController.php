<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class AdminController extends Controller
{
    function settingsTelegram(Request $request)
    {
        if (Storage::disk('local')->exists('settings.json')) {
            $json = Storage::disk('local')->get('settings.json');
        }

        if ($request->getMethod() == 'POST' && $json = $request->input('json')) {
            try {
                json_decode($json, null, 512, JSON_THROW_ON_ERROR);
                Storage::disk('local')->put('settings.json', $json);
                session()->flash('message', 'success');
            } catch (\Throwable $th) {
                dd($th->getMessage());
                return redirect()->back()->with('message', 'error json format');
            }           
        }

        return view('admin.settings.telegram', [
            'title' => 'Settings',
            'json' => $json ?? '',
        ]);  
    }
}
