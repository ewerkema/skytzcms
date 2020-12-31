<?php

namespace App\Http\Controllers;

use App\Models\ExcelTable;
use App\Services\TableGeneratorService;
use Illuminate\Http\Request;
use Validator;

class ExcelTablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return ExcelTable::all();
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
            'name' => 'required|max:255',
            'file' => 'nullable|file|mimes:xlsx,xls,csv,txt'
        ], [], [
            'name' => 'Naam',
            'file' => 'Bestand',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param TableGeneratorService    $generatorService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TableGeneratorService $generatorService)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $input = $this->processFile($request, $input, $generatorService);

        $excelTable = ExcelTable::create($input);

        return response()->json($excelTable, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $excelTable = ExcelTable::findOrFail($id);

        return response()->json($excelTable);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param ExcelTable               $excelTable
     * @param TableGeneratorService    $generatorService
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExcelTable $excelTable, TableGeneratorService $generatorService)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $input = $this->processFile($request, $input, $generatorService);

        if (!$excelTable->update($input))
            return response()->json(['message' => 'Updaten van het excel tabel is niet gelukt.'], 500);

        return response()->json($excelTable);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $excelTable = ExcelTable::findOrFail($id)->delete();

        return response()->json(['message' => 'Excel tabel is succesvol verwijderd']);
    }

    /**
     * @param Request               $request
     * @param array                 $input
     * @param TableGeneratorService $generatorService
     *
     * @return array
     */
    private function processFile(Request $request, array $input, TableGeneratorService $generatorService)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $input['table'] = $generatorService->generateTable($file);
            unset($input['file']);
        }

        return $input;
    }
}
