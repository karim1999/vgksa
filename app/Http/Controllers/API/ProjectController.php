<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Project::orderBy('id', 'desc')->get();
    }

    public function paginate()
    {
        //
        return Project::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user= User::findOrFail(auth()->user()->id);
        $category= Category::findOrFail($request->category_id);
        $project= new Project($request->all());
        $project->category()->associate($category);
        $user->projects()->save($project);
        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));

    }

    /**
     * Display the specified resource.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
        $project->update($request->all());

        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function delete(Project $project)
    {
        //
        $project->delete();

        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    }
}
