<?php

namespace App\Http\Controllers;

use App\Models\ProjectGroup;
use Illuminate\Http\Request;
use Validator;
use Response;
use Input;

class ProjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return ProjectGroup::all();
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
        ], [], [
            'title' => 'Titel',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $projectGroup = ProjectGroup::create($input);

        return response()->json($projectGroup, 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $projectGroup = ProjectGroup::findOrFail($id);
        $projects = $projectGroup->projects()->get();

        if ($projects != null) {
            foreach ($projects as $project) {
                $project->delete();
            }
        }
        $projectGroup->delete();

        return response()->json(['message' => 'Project groep is succesvol verwijderd']);
    }
}
