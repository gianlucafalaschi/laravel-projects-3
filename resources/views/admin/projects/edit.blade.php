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

<form action="{{ route('admin.projects.update', ['project' => $project->slug]) }}" method="POST" enctype="multipart/form-data">
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
        <label for="cover_image" class="form-label">Image</label>
        <input class="form-control" type="file" id="cover_image" name="cover_image">
    </div>      
    
    @if ($project->cover_image)
        <img class="img-fluid" src="{{ asset('storage/' . $project->cover_image) }}" alt=" {{ $project->name }}"> 
    @else
        <small>No image</small>
    @endif
    
    <div class="mb-3">
        <label for="type_id" class="form-label">Type</label>
        <select class="form-select" id="type_id" name="type_id">
          <option value="">Open this select menu</option>
          @foreach ($types as $type)
            {{-- se lo user ha messo un valore ma l'edit è fallito stampa popola con il valore dell'old, altrimenti popola con il valore del database --}}
            <option @selected($type->id == old('type_id', $project->type_id)) value="{{ $type->id}}">{{$type->name}}</option>
          @endforeach
        </select>
      </div>

    <div class="mb-3">
        <label for="summary" class="form-label">Summary</label>
        <textarea class="form-control" id="summary" name="summary" rows="8">{{ old('summary', $project->summary) }}</textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
@endsection