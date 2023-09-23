<?php

namespace App\Services;

use App\Enums\SourceType;
use App\Models\DirtyCarParametersData;
use App\Models\PropertyDictionary;
use App\Models\PropertyValueDictionary;
use Illuminate\Support\Collection;

final class ParametersService
{
    function getAllApprouvedParameters()
    {
        $dictionary = PropertyDictionary::get();

        return $dictionary->toArray();
    }

    function getCleanValues(Collection $propertyValues, string $lang = 'ru'): array
    {
        $answer = [];
        foreach ($propertyValues as $property => $value) {
            $propertyArray = [
                'property' => $property,
                'value' => $value,
                'valid' => false,
            ];
            $param = $this->getParameter($property);
            if ($param) {
                $dictionary = PropertyDictionary::where('name',$param->name)->where('group', SourceType::Car)->first();
                if ($dictionary) {
                    if ($dictionary->is_dictionary == false) {
                        $propertyArray['valid'] = true;
                        $propertyArray['value'] = $this->removeSimvols($value);
                    }else{
                        $dictionaryValue = PropertyValueDictionary::where('property_dictionaries_uuid', $dictionary->uuid)
                            ->where('name', $value)
                            ->where('group', SourceType::Car)
                            ->first();
                        if ($dictionaryValue) {
                            $propertyArray['valid'] = true;
                            if ($lang == 'ru') {
                                $propertyArray['property'] = $dictionary->ru;
                                $propertyArray['name'] = $param->name;
                                $propertyArray['value'] = $dictionaryValue->ru;
                            }
                        }
                    }
                }
            }
            $answer[] = $propertyArray;
        }

        return $answer;
    }

    function getParameter(string $property): Null|DirtyCarParametersData {
        return DirtyCarParametersData::where('property', $property)->where('is_appruved', true)->first();
    }

    function getPrimaryParametersList(): array 
    {
        $parametersKeyList = ['brend'];
        $result = [];
        foreach ($parametersKeyList as $one) {
            $result[] = PropertyDictionary::where('name', $one)->where('group', SourceType::Car)->first();
        }
        return $result;
    }

    function getBrendDirtyParametersKeys(): array 
    {
        $parameters = DirtyCarParametersData::where('name','brand')->get();
        return $parameters->pluck('property')->toArray();
    }

    private function removeSimvols(string $value) : string {
        return preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $value);
    }
}
