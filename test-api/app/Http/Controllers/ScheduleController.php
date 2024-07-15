<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Schedule as ScheduleResource;

/**
 * @OA\Schema(
 *     schema="Schedule",
 *     type="object",
 *     title="Schedule",
 *     required={"title", "type", "description", "status", "start_at", "conclusion_at", "user_id"},
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="title", type="string", example="Meeting"),
 *         @OA\Property(property="type", type="string", example="meeting"),
 *         @OA\Property(property="description", type="string", example="Team meeting"),
 *         @OA\Property(property="status", type="boolean", example=true),
 *         @OA\Property(property="start_at", type="string", format="date-time", example="2024-07-15T00:00:00Z"),
 *         @OA\Property(property="conclusion_at", type="string", format="date-time", example="2024-07-15T01:00:00Z"),
 *         @OA\Property(property="user_id", type="integer", example=1)
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="ScheduleStoreRequest",
 *     type="object",
 *     title="Schedule Store Request",
 *     required={"title", "type", "description", "status", "start_at", "conclusion_at", "user_id"},
 *     properties={
 *         @OA\Property(property="title", type="string", example="Meeting"),
 *         @OA\Property(property="type", type="string", example="meeting"),
 *         @OA\Property(property="description", type="string", example="Team meeting"),
 *         @OA\Property(property="status", type="boolean", example=true),
 *         @OA\Property(property="start_at", type="string", format="date-time", example="2024-07-15T00:00:00Z"),
 *         @OA\Property(property="conclusion_at", type="string", format="date-time", example="2024-07-15T01:00:00Z"),
 *         @OA\Property(property="user_id", type="integer", example=1)
 *     }
 * ) 
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class ScheduleController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/schedules",
     *     summary="Get list of schedules",
     *     tags={"Schedules"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Schedule"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function index()
    {
        $schedules = Schedule::with('user')->get();

        return $this->sendResponse(ScheduleResource::collection($schedules), 'Schedules Retrieved Successfully.');
    }

    
    /**
     * @OA\Get(
     *     path="/api/get_all_by_user/list",
     *     summary="Get list of schedules by logged user",
     *     tags={"Schedules"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Schedule"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function get_all_by_user()
    {
        $schedules = Schedule::with('user')->where('user_id', auth()->user()->id)->get();

        return $this->sendResponse(ScheduleResource::collection($schedules), 'Schedules Retrieved Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * @OA\Post(
     *     path="/api/schedules",
     *     summary="Create a new schedule",
     *     tags={"Schedules"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ScheduleStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Schedule")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $input = $request->all();
    
        // Validação inicial
        $validator = Validator::make($input, [
            'title'             => ['required', 'string'],
            'type'              => ['required', 'string', 'max:95'],
            'description'       => ['required', 'string', 'max:550'],
            'status'            => ['required', 'boolean'],
            'start_at'          => ['required', 'date'],
            'conclusion_at'     => ['required', 'date'],
            'user_id'           => ['required', 'exists:users,id'],
        ]);
    
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $start_at = Carbon::parse($input['start_at']);
        $conclusion_at = Carbon::parse($input['conclusion_at']);
        $status = filter_var($input['status'], FILTER_VALIDATE_BOOLEAN);
    
        if ($start_at->isWeekend() || $conclusion_at->isWeekend()) {
            return $this->sendError('Validation Error.', ['Both start and conclusion dates must be on a weekday.']);
        }
    
        if ($status) {
            $user_id = $input['user_id'];
            $conflictingsVerify = Schedule::where('user_id', $user_id)
                ->where('status', true)
                ->where(function ($query) use ($start_at, $conclusion_at) {
                    $query->whereBetween('start_at', [$start_at, $conclusion_at])
                        ->orWhereBetween('conclusion_at', [$start_at, $conclusion_at])
                        ->orWhere(function ($query) use ($start_at, $conclusion_at) {
                            $query->where('start_at', '<', $start_at)
                                ->where('conclusion_at', '>', $conclusion_at);
                        });
                })
                ->exists();
    
            if ($conflictingsVerify) {
                return $this->sendError('Validation Error.', ['The provided dates are conflicting with another schedule for the same user.']);
            }
        }
    
        $schedule = Schedule::create($input);
    
        return $this->sendResponse(new ScheduleResource($schedule), 'Schedule Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);
  
        if (is_null($schedule)) {
            return $this->sendError('Schedule not found.');
        }
   
        return $this->sendResponse(new ScheduleResource($schedule), 'Schedule Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title'             => ['required', 'string'],
            'type'              => ['required', 'string', 'max:95'],
            'description'       => ['required', 'string', 'max:550'],
            'status'            => ['required', 'boolean'],
            'start_at'          => ['required', 'date'],
            'conclusion_at'     => ['required', 'date'],
            'user_id'           => ['required', 'exists:users,id'],
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $start_at = Carbon::parse($input['start_at']);
        $conclusion_at = Carbon::parse($input['conclusion_at']);
        $status = filter_var($input['status'], FILTER_VALIDATE_BOOLEAN);
    
        if ($start_at->isWeekend() || $conclusion_at->isWeekend()) {
            return $this->sendError('Validation Error.', ['Both start and conclusion dates must be on a weekday.']);
        }
        
        $schedule = Schedule::find($id);
    
        if ($status && ($schedule->start_at !== $input['start_at'] OR $schedule->conclusion_at !== $input['conclusion_at'])) {
            $user_id = $input['user_id'];
            $conflictingsVerify = Schedule::where('user_id', $user_id)
                ->where('id', '!=', $schedule->id)
                ->where('status', true)
                ->where(function ($query) use ($start_at, $conclusion_at) {
                    $query->whereBetween('start_at', [$start_at, $conclusion_at])
                        ->orWhereBetween('conclusion_at', [$start_at, $conclusion_at])
                        ->orWhere(function ($query) use ($start_at, $conclusion_at) {
                            $query->where('start_at', '<', $start_at)
                                ->where('conclusion_at', '>', $conclusion_at);
                        });
                })
                ->exists();
    
            if ($conflictingsVerify) {
                return $this->sendError('Validation Error.', ['The provided dates are conflicting with another schedule for the same user.']);
            }
        }
          
        $schedule->title            = $input['title'];
        $schedule->type             = $input['type'];
        $schedule->description      = $input['description'];
        $schedule->status           = $input['status'];
        $schedule->start_at         = $input['start_at'];
        $schedule->conclusion_at    = $input['conclusion_at'];
        $schedule->save();
   
        return $this->sendResponse(new ScheduleResource($schedule), 'Schedule Updated Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_status(Request $request, $id)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'status'    => ['required', 'boolean'],
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $schedule = Schedule::find($id);
        
        $start_at = Carbon::parse($schedule->start_at);
        $conclusion_at = Carbon::parse($schedule->conclusion_at);
        $status = filter_var($input['status'], FILTER_VALIDATE_BOOLEAN);
    
        if ($start_at->isWeekend() || $conclusion_at->isWeekend()) {
            return $this->sendError('Validation Error.', ['Both start and conclusion dates must be on a weekday.']);
        }
    
        if ($status) {
            $conflictingsVerify = Schedule::where('user_id', $schedule->user_id)
                ->where('status', true)
                ->where(function ($query) use ($start_at, $conclusion_at) {
                    $query->whereBetween('start_at', [$start_at, $conclusion_at])
                        ->orWhereBetween('conclusion_at', [$start_at, $conclusion_at])
                        ->orWhere(function ($query) use ($start_at, $conclusion_at) {
                            $query->where('start_at', '<', $start_at)
                                ->where('conclusion_at', '>', $conclusion_at);
                        });
                })
                ->exists();
    
            if ($conflictingsVerify) {
                return $this->sendError('Validation Error.', ['The provided dates are conflicting with another schedule for the same user.']);
            }
        }

        $schedule->status = $input['status'];
        $schedule->save();
   
        return $this->sendResponse(new ScheduleResource($schedule), 'Schedule Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
   
        return $this->sendResponse([], 'Schedule Deleted Successfully.');
    }
}