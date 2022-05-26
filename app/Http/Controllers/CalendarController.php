<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Http\Requests\StoreUpdateCalendar;
use App\Http\Resources\Calendar as ResourcesCalendar;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResourcesCalendar::collection(
            Calendar::where('user_id',auth()->user()->id)
            ->get()
            ->sortBy('title')
            ->sortBy('created_at')
            ->sortBy('due_date')
            )->response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCalendar $request)
    {
       $request->validated();   
       return $request->saveCalendar($request);            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {       
        $user = auth()->user();
        $calendar = $user->calendar->where('id',$calendar->id);
        if(!$calendar->toArray()){
            return response()->json(['message'=>"Calendar id is not found."], Calendar::CALENDAR_NOT_FOUND);   
        }
        return  new ResourcesCalendar($calendar->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateCalendar $request, $id)
    {
        $request->validated();      
        return $request->saveCalendar($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        if($calendar->delete()) {
            return response()->json(['data'=>new ResourcesCalendar($calendar),'message'=>'Successfully deleted.'], Calendar::CALENDAR_SUCCESSFULLY_DELETED);
        }       
    }
}
