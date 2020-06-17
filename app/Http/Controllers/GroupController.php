<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use \Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('group');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response('hahahahahah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $request->validated();

        try {
            $group = new Group;
            $group->name = $request->name;
            $group->user_id = $request->user_id;

            $group->save();
        } catch (Exception $e) {
            throw $e;
            return response(null, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GroupRequest $request, $id)
    {
        // DB::enableQueryLog();
        // // query ~~~ 
        // $quries = DB::getQueryLog();

        $request->validated();

        $group = Group::where('idx', $id)->select('idx', 'name')->first();
        $statusCode = empty($group) ? 400 : 200;
        return response(['data' => $group], $statusCode);
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
        $group = Group::find($id);

        if (empty($group)) {
            return response(null, 400);
        }

        $group->name = $request->name;

        $group->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return response(null, 400);
        }
        Group::destroy($id);
    }
}
