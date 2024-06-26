<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str; // importo classe Str per ricavare slug
use Illuminate\Validation\Rule; // importo class Rule per usare ignore nella validation dell'update
use Illuminate\Support\Facades\Storage; // importo storage per usare storage per le immagini
use App\Models\Type;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        //dd($projects);
        return view('admin.projects.index', compact('projects'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // validation
        $validated = $request->validate([
            'name' => 'required|min:5|max:250|unique:projects,name|',
            'client_name' => 'nullable|min:5',
            'summary' => 'nullable|min:10', 
            'cover_image' => 'nullable|image|max:256'
        ]);


        $formData = $request->all();
        // per aggiungere immagini alla colonna cover_image
        if($request->hasFile('cover_image')) {
            // fare l'upload del file immagine nella cartella pubblica
            $img_path = Storage::disk('public')->put('project_images', $formData['cover_image']);
            // salvare nel database il path del file caricato nella colonna cover_image
            $formData['cover_image'] = $img_path;
        }
        


        //creo nuova istanza di project
        $newProject = new Project();
        $newProject->slug = Str::slug($formData['name'], '-');
        $newProject->fill($formData);
        $newProject->save();

        //dd($formData);
        return redirect()->route('admin.projects.show', ['project' => $newProject->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {   
        
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {   
        //dd($project);
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {   
        // validation
        $validated = $request->validate([

            'name' => [
                'required',
                'min:5',
                'max:250',
                Rule::unique('projects')->ignore($project), // evita che la regola unique venga applicata se l'utente modifica un project tenendo lo stesso name  
            ],
            'client_name' => 'nullable|min:5',
            'summary' => 'nullable|min:10', 
            'cover_image' => 'nullable|image|max:256'
        ]);

        $formData = $request->all();

        // per aggiungere immagini alla colonna cover_image
        if($request->hasFile('cover_image')) {
            // Se c'è l'immagine vecchia la cancello dalla cartella
            if($project->cover_image) {
                Storage::delete($project->cover_image);
            }

            // Fare l'upload del file nella cartella pubblica
            $img_path = Storage::disk('public')->put('project_images', $formData['cover_image']);
            // Salvare nel db il path del file caricato nella colonna cover_image
            $formData['cover_image'] = $img_path;
        }

        $formData['slug'] = Str::slug($formData['name'], '-');
        //dd($formData);
        $project->update($formData);


        return redirect()->route('admin.projects.show', ['project' => $project->slug]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //dd($project);
        $project->delete();

        return redirect()->route('admin.projects.index');
    }
}
