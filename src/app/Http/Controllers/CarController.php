<?php 

namespace App\Http\Controllers;

use App\Classes\Messages\MessageCar;
use App\Enums\SendType;
use App\Enums\Sources;
use App\Enums\SourceType;
use App\Models\CompleteMessage;
use App\Models\DirtyCarData;
use App\Models\DirtyCarParametersData;
use App\Models\PropertyDictionary;
use App\Models\PropertyValueDictionary;
use App\Services\MessageService;
use App\Services\ParametersService;
use App\Services\SenderService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{

    function __construct(
        private ParametersService $parametersService,
        private MessageService    $messageService,
        private SenderService     $senderService,
    )
    {}

    public function list(string $model): View|Factory
    {
        if (!$model) {
            throw new Exception("Error Processing Request");
        }

        $source = Sources::from($model);
        if ($model == Sources::Polovniautomobili->value) {
            $list = DirtyCarParametersData::join('dirty_car_data', 'dirty_car_data.id', '=', 'dirty_car_parameters_data.car_id')
                ->where('dirty_car_data.source', $source->name)
                ->where('dirty_car_parameters_data.property', 'Broj oglasa:')
                ->orderByDesc('dirty_car_parameters_data.value')
                ->select('dirty_car_data.*')
                ->paginate(15);
        }else{
            $list = DirtyCarData::where('source', $source->name)->orderByDesc('id')->paginate(15);
        }
        $count = DirtyCarData::where('source', $source->name)->count();

        $engineTypes = DirtyCarParametersData::where('property', 'Gorivo')->distinct()->get(['value']);

        return view('pages.car.table', [
            'title' => $model,
            'list' => $list,
            'count' => $count,
            'fillter' => [
                'engineTypes' => $engineTypes->pluck('value'),
            ]
        ]);
    }

    function form(Request $request, DirtyCarData $model): View|Factory
    {
        $message = new MessageCar();
        $message->id = $this->messageService->getMessageCarId($model);

        $message->setImages(explode(',', $model->images));
        $message->price = $model->price;
        $message->name = $model->name;
        $message->url = $model->url;


        $model->load('dirtyCarParametersData');

        $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
        $message = $this->messageService->updateMessageDto($message,$cleanParams);

        $isPublished = CompleteMessage::where('model', $model::class)->where('model_id', $model->id)->exists();

        return view('pages.car.telegram-form', [
            'title' => 'Send to telegramm',
            'model' => $model,
            'message' => $message,
            'is_published' => $isPublished,
            'chats' => []
        ]);
    }

    function send(Request $request, DirtyCarData $model): RedirectResponse
    {
        try {
            $chatId = $request->input('chatId');

            $message = $this->messageService->getCarMessage($request, $model);
    
            $this->senderService->sendTelegram($message,$chatId, SendType::Handle->value);
            return redirect()->route('car.list', ['model' => $model->source])->with('message', 'success');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('message', 'error');
        }
    }

    function editDictionary(): View|Factory
    {
        /** @var LengthAwarePaginator|DirtyCarParametersData[] $dirtyCarParametersData */
        $dirtyCarParametersData = DirtyCarParametersData::distinct('property')->groupBy('property')->select(['property'])->paginate(15);

        foreach ($dirtyCarParametersData as &$parameter) {
            /** @use  Collection<DirtyCarParametersData> $exist */
            $exist = DirtyCarParametersData::where('is_appruved', true)->where('property', $parameter->property)->get();
            if ($exist->isNotEmpty()) {
                $value_ru = PropertyDictionary::where('name', $exist->first()->name)->first(['ru'])->toArray()['ru'];
                $parameter->name  = $exist->first()->name;
                $parameter->value_ru = $value_ru;
            }else{
                $parameter->name  = '';
            }
        }
        return view('pages.car.edit-dictionary', [
            'title' => 'Edit dictionary',
            'list' => $dirtyCarParametersData
        ]);
    }

    function listDictionaryValues(Request $request): View|Factory|RedirectResponse
    {
        $property = request()->route('property');
        $propertyData = DB::table('dirty_car_parameters_data as data')
            ->join('property_dictionaries as dict', 'data.name', '=', 'dict.name')
            ->where('data.is_appruved', true)->where('data.property', $property)->first();

        if (empty($propertyData)) {
            return redirect()->back()->with('message', 'error');
        }

        $list = DirtyCarParametersData::where('property', $property)
        ->distinct('value')
        ->select(['property', 'value'])
        ->paginate(15);

        $list->each(function(DirtyCarParametersData $item) use ($propertyData){
            $existDictionaryValue = PropertyValueDictionary::where('property_dictionaries_uuid', $propertyData->uuid)
            ->where('name', $item->value)
            ->first();
            if (!empty($existDictionaryValue)) {
                $item->value_ru = $existDictionaryValue->ru;
                $item->value_en = $existDictionaryValue->en;
                $item->value_rs = $existDictionaryValue->rs;
            }
        });

        return view('pages.car.edit-dictionary-values', [
            'title' => 'Edit dictionary values',
            'list' => $list,
            'property' => $propertyData,
        ]);
    }

    function saveDictionaryProperty(Request $request, string $property): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'value_ru' => 'required|string',
            'value_en' => 'string',
            'value_rs' => 'string',
        ]);

        DB::transaction(function() use ($request, $property){

            $name = $request->input('name');
            $value_ru = $request->input('value_ru');
            $value_en = $request->input('value_en', '');
            $value_rs = $request->input('value_rs', '');

            $model = DirtyCarParametersData::where('property', $property)->first();
            $model->name = $name;
            $model->is_appruved = true;
            $model->save();

            if ($existDictionaty = PropertyDictionary::where('name', $name)->first()) {
                $existDictionaty->ru = $value_ru;
                $existDictionaty->en = $value_en;
                $existDictionaty->rs = $value_rs;
                $existDictionaty->save();
            }else{
                $newDictionary = new PropertyDictionary();
                $newDictionary->name = $name;
                $newDictionary->group = SourceType::Car->value;
                $newDictionary->ru = $value_ru;
                $newDictionary->en = $value_en;
                $newDictionary->rs = $value_rs;
                $newDictionary->save();
            }
        });
        
        return redirect()->back()->with('message', 'success');
    }

    function listDictionaryValuesSave(Request $request, string $name): RedirectResponse
    {
        $request->validate([
            'original_value' => 'required|string',
            'value_ru' => 'string',
            'value_en' => 'string',
            'value_rs' => 'string',
        ]);

        $dictionary = PropertyDictionary::where('name', $name)->first();

        $originalValue = $request->input('original_value');
        $existDictionaryValue = PropertyValueDictionary::where('property_dictionaries_uuid', $dictionary->uuid)
        ->where('name', $originalValue)
        ->first();
        if (empty($existDictionaryValue)) {
            $newDictionaryValue =  new PropertyValueDictionary();
            $newDictionaryValue->property_dictionaries_uuid = $dictionary->uuid;
            $newDictionaryValue->name = $originalValue;
            $newDictionaryValue->group = $dictionary->group;
            $newDictionaryValue->save();
            $existDictionaryValue = $newDictionaryValue;
        }

        if ($value = $request->input('value_ru', '')) {
            $existDictionaryValue->ru = $value;
        }
        if ($value = $request->input('value_en', '')) {
            $existDictionaryValue->en = $value;
        }
        if ($value = $request->input('value_rs', '')) {
            $existDictionaryValue->rs = $value;
        }
        $existDictionaryValue->save();

        return redirect()->back()->with('message', 'success');
    }

    function switchDictionaryProperty(Request $request, string $uuid): RedirectResponse
    {
        $isChecked = $request->get('is_dictionary');
        PropertyDictionary::where('uuid', $uuid)->update(['is_dictionary' => (bool)$isChecked]);
        return redirect()->back()->with('message', 'success');
    }
}