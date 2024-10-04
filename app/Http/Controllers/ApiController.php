<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;
use App\Models\Utility;
use App\Models\Projectstages;
use App\Models\ProjectTask;
use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
         $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string'
        ]);

        if (!\Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }
        $user=\Auth::user();

        if($user->type == 'company')
        {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            $plan      = Plan::find($user->plan);

            if($user->plan != $free_plan->id)
            {
                if(date('Y-m-d') > $user->plan_expire_date && $plan->duration != 'Lifetime')
                {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $clients   = User::where('type', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $employees = User::where('type', 'employee')->where('created_by', '=', \Auth::user()->creatorId())->get();

                    if($free_plan->max_client == -1)
                    {
                        foreach($clients as $client)
                        {
                            $client->is_active = 1;
                            $client->save();
                        }
                    }
                    else
                    {
                        $clientCount = 0;
                        foreach($clients as $client)
                        {
                            $clientCount++;
                            if($clientCount <= $free_plan->max_client)
                            {
                                $client->is_active = 1;
                                $client->save();
                            }
                            else
                            {
                                $client->is_active = 0;
                                $client->save();
                            }
                        }

                    }


                    if($free_plan->max_employee == -1)
                    {
                        foreach($employees as $employee)
                        {
                            $employee->is_active = 1;
                            $employee->save();
                        }
                    }
                    else
                    {
                        $employeeCount = 0;
                        foreach($employees as $employee)
                        {
                            $employeeCount++;
                            if($employeeCount <= $free_plan->max_employee)
                            {
                                $employee->is_active = 1;
                                $employee->save();
                            }
                            else
                            {
                                $employee->is_active = 0;
                                $employee->save();
                            }
                        }
                    }
                }
            }

        }

        $settings              = Utility::settings(auth()->user()->id);

        $settings = [
            'shot_time'=> isset($settings['interval_time'])?$settings['interval_time']:10,
        ];
        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user'=>auth()->user(),
            'settings' =>$settings,
        ],'Login successfully.');
    }

    public function register(Request $request)
    {
        // Validación de los datos entrantes
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Si la validación falla, retornar un mensaje de error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422); // HTTP 422 Unprocessable Entity
        }

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'company',
                'lang' => Utility::getValByName('default_language') ?: 'en',
                'plan' => Plan::first()->id,
                'created_by' => 1,
            ]);
            $user->email_verified_at = date("H:i:s");
            $user->save();
            $user->userDefaultData();

            // Retornar una respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            // Manejar cualquier excepción durante el registro
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error durante el registro, intenta de nuevo',
                'error' => $e->getMessage()
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([],'Tokens Revoked');
    }

    public function getProjects(Request $request){
        $user = \Auth::user();


            $project_s = Project::with('tasks')->select(
                [
                    'title',
                    'id',
                    'client',
                ]
            )->where('created_by', $user->creatorId())->get()->toArray();


        return $this->success([
            'projects' => $project_s,
        ],'Get Project List successfully.');
    }

    public function addTracker(Request $request){

        $user = auth()->user();
        if($request->has('action') && $request->action == 'start'){

            $validatorArray = [
                'task_id' => 'required|integer',
            ];
            $validator      = \Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return $this->error($validator->errors()->first(), 401);
            }
            $task= ProjectTask::find($request->task_id);
            if(empty($task)){
                return $this->error('Invalid task', 401);
            }

            $project_id = isset($task->project_id)?$task->project_id:'';
            TimeTracker::where('created_by', '=', $user->id)->where('is_active', '=', 1)->update(['end_time' => date("Y-m-d H:i:s")]);

            $track['name']        = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['project_id']  = $project_id;
            $track['is_billable'] =  $request->has('is_billable')? $request->is_billable:0;
            $track['tag_id']      = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['start_time']  = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
            $track['task_id']     = $request->has('task_id') ? $request->input('task_id') : '';
            $track['created_by']  = $user->id;
            $track                = TimeTracker::create($track);
            $track->action        ='start';
            return $this->success( $track,'Track successfully create.');
        }else{
            $validatorArray = [
                'task_id' => 'required|integer',
                'traker_id' =>'required|integer',
            ];
            $validator      = Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return Utility::error_res($validator->errors()->first());
            }
            $tracker = TimeTracker::where('id',$request->traker_id)->first();
          
            if($tracker)
            {
                $tracker->end_time   = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
                $tracker->is_active  = 0;
                $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
                $tracker->save();
                return $this->success( $tracker,'Stop time successfully.');
            }
        }

    }

    public function uploadImage(Request $request){
        $user = auth()->user();
        $image_base64 = base64_decode($request->img);
        $file =$request->imgName;
        if($request->has('tracker_id') && !empty($request->tracker_id)){
            $app_path = storage_path('uploads/traker_images/').$request->tracker_id.'/';
            if (!file_exists($app_path)) {
                mkdir($app_path, 0777, true);
            }

        }else{
            $app_path = storage_path('uploads/traker_images/');
            if (is_dir($app_path)) {
                mkdir($app_path, 0777, true);
            }
        }
        $file_name =  $app_path.$file;
        file_put_contents( $file_name, $image_base64);
        $new = new TrackPhoto();
        $new->track_id = $request->tracker_id;
        $new->user_id  = $user->id;
        $new->img_path  = 'uploads/traker_images/'.$request->tracker_id.'/'.$file;
        $new->time  = $request->time;
        $new->status  = 1;
        $new->save();
        return $this->success( [],'Uploaded successfully.');
    }

}
