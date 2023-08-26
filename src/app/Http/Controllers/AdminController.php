<?php

namespace App\Http\Controllers;

use App\Services\TelegramSettingsService;
use Illuminate\Http\Request;

final class AdminController extends Controller
{
    function settingsTelegram(Request $request, TelegramSettingsService $telegramSettingsService)
    {
        if ($request->getMethod() == 'POST' && $json = $request->input('json')) {
            try {
                json_decode($json, null, 512, JSON_THROW_ON_ERROR);
                $telegramSettingsService->saveJson($json);
                session()->flash('message', 'success');
            } catch (\Throwable $th) {
                return redirect()->back()->with('message', 'error json format');
            }           
        }

        return view('admin.settings.telegram', [
            'title' => 'Settings',
            'json' => $telegramSettingsService->getJson(),
        ]);  
    }
}
