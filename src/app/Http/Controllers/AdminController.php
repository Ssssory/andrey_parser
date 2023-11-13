<?php

namespace App\Http\Controllers;

use App\Enums\SendScop;
use App\Enums\Transport;
use App\Enums\SourceType;
use App\Models\Bot;
use App\Models\Group;
use App\Services\TelegramSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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
            'scop' => SendScop::cases(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
    }

    function saveBotSettings(Request $request)
    {
        if (isset($request->id)) {
            $request->validate([
                'name' => 'required',
                'token' => 'required',
                'type' => 'required|in:' . implode(',', SourceType::getValues()),
                'transport' => 'required|in :' . implode(',', [Transport::Telegram->value]),
            ]);
            Bot::where('id', $request->get('id'))->update(Arr::except($request->all(), '_token'));
            return redirect()->back()->with('success', 'Bot updated');
        }else{
            $request->validate([
                'name' => 'required',
                'token' => 'required|unique:bots',
                'type' => 'required|in:'.implode(',', SourceType::getValues()),
                'transport' => 'required|in :'.implode(',', [Transport::Telegram->value]),
            ]);
            $bot = Bot::create(array_merge($request->all(), [
                'owner' => Auth::user()->name,
            ]));
        }

        
        return redirect()->back()->with('success', 'Bot created');
    }

    function editBotSettings(Request $request, Bot $bot)
    {
        return view('admin.settings.bot-edit', [
            'title' => 'Edit bot',
            'bot' => $bot,
            'scop' => SendScop::cases(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
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
            'scop' => SendScop::cases(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
    }

    function saveGroupSettings(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'group_id' => 'required',
            'type' => 'required|in:' . implode(',', SourceType::getValues()),
            'scop' => 'required|in:' . implode(',', SendScop::getValues()),
            'transport' => 'required|in :' . implode(',', [Transport::Telegram->value]),
        ]);

        if (isset($request->id)) {
            Group::where('id', $request->get('id'))->update(Arr::except($request->all(), '_token'));
            return redirect()->back()->with('success', 'Group updated');
        }else{
            Group::create(array_merge($request->all()));
            return redirect()->back()->with('success', 'Group created');
        }
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

    function editGroupSettings(Request $request, Group $group) 
    {
        return view('admin.settings.group-edit', [
            'title' => 'Edit group',
            'group' => $group,
            'scop' => SendScop::cases(),
            'transport' => Transport::cases(),
            'types' => SourceType::cases(),
        ]);
        
    }
}
