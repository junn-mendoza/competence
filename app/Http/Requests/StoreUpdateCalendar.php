<?php

namespace App\Http\Requests;

use App\Models\Calendar;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BaseRequest;
use App\Http\Resources\Calendar as ResourcesCalendar;

class StoreUpdateCalendar extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'due_date' => 'required|date',
        ];
    }

    public function saveCalendar($calFld, $id = null): JsonResponse
    {
        // dump($id);
        // dd(gettype($calFld));
        $fld = $calFld->toArray();
        if($this->method() == 'POST'){
            Calendar::create(array_merge($fld,['user_id'=>auth()->user()->id]));
            return response()->json(['data'=> new ResourcesCalendar($calFld),'message'=>"Successfully created."], Calendar::CALENDAR_SUCCESSFULLY_CREATED);
        } else {
           // dd($fld);
            Calendar::where('id',$id)->update($fld);
        }       
        return response()->json(['data'=> new ResourcesCalendar($calFld),'message'=>"Successfully updated."], Calendar::CALENDAR_SUCCESSFULLY_UPDATED);
    }
    
}
