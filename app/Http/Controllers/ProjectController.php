<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Validator;
use Response;
use Input;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::latest()->get();
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
            'project_group_id' => 'required|exists:project_groups,id',
            'title' => 'required|max:255',
            'body' => 'required',
            'published' => 'required|boolean',
        ], [], [
            'project_group_id' => 'Project groep',
            'title' => 'Titel',
            'summary' => 'Introductie voor het project',
            'body' => 'Project beschrijving',
            'address' => 'Project adres',
            'published' => 'Gepubliceerd',
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

        $project = Project::create($input);

        if (isset($input['image_ids'])) {
            $project->addImages($input['image_ids']);
        }

        return response()->json($project, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $project->removeImages();

        if (isset($input['image_ids'])) {
            $project->addImages($input['image_ids']);
        }

        if (!$project->update($input))
            return response()->json(['message' => 'Updaten van het project is niet gelukt.'], 500);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project is succesvol verwijderd']);
    }
}
