<?php

namespace App\Http\Controllers;

use App\Enums\Transport;
use App\Enums\SourceType;
use App\Models\Bot;
use App\Models\Group;
use App\Services\TelegramSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Configuration\Source;

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

    function botSettings(Request $request)
    {
        return view('admin.settings.bot', [
            'title' => 'Manage bots',
            'bots' => Bot::all(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
    }

    function saveBotSettings(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'token' => 'required|unique:bots',
            'type' => 'required|in:'.implode(',', SourceType::getValues()),
            'transport' => 'required|in :'.implode(',', [Transport::Telegram->value]),
        ]);

        $bot = Bot::create(array_merge($request->all(), [
            'owner' => Auth::user()->name,
        ]));
        return redirect()->back()->with('success', 'Bot created');
    }

    function activeBotSettings(Request $request, Bot $bot) 
    {
        $bot->is_active = !$bot->is_active;
        $bot->save();
        return redirect()->back()->with('success', 'Bot updated');
    }

    function deleteBotSettings(Request $request, Bot $bot) 
    {
        $bot->delete();
        return redirect()->back()->with('success', 'Bot deleted');
    }

    function groupSettings(Request $request)
    {
        return view('admin.settings.group', [
            'title' => 'Manage bots',
            'groups' => Group::all(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
    }

    function saveGroupSettings(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'group_id' => 'required',
            'type' => 'required|in:' . implode(',', SourceType::getValues()),
            'transport' => 'required|in :' . implode(',', [Transport::Telegram->value]),
        ]);

        Group::create(array_merge($request->all()));
        return redirect()->back()->with('success', 'Bot created');
    }

    function activeGroupSettings(Request $request, Group $group)
    {
        $group->is_active = !$group->is_active;
        $group->save();
        return redirect()->back()->with('success', 'Group updated');
    }

    function deleteGroupSettings(Request $request, Group $group)
    {
        $group->delete();
        return redirect()->back()->with('success', 'Group deleted');
    }
}
