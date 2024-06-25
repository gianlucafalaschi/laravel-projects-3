@extends('layouts.admin')

@section('content')
<h1>Edit project: {{ $project->name }}</h1> 
 
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h4><strong>Id</strong>: {{ $project->id }}</h4>

<form action="{{ route('admin.projects.update', ['project' => $project->slug]) }}" method="POST" >
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        {{-- se c'e' l'old stampa l'old, altrimenti stampa il $project->name del database --}}
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $project->name) }}"> 
    </div>

    <div class="mb-3">
        <label for="client_name" class="form-label">Client name</label>
        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}">
    </div>
      
    <div class="mb-3">
        <label for="summary" class="form-label">Summary</label>
        <textarea class="form-control" id="summary" name="summary" rows="8">{{ old('summary', $project->summary) }}</textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
@endsection