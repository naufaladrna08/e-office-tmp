<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Classes\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;

class PhotoController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $validate = $request->validate([
      'file' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
    ]);

    $name = Auth::id() . '-' . time() . '.' . $request->file->getClientOriginalExtension();
    $path = $request->file('file')->storeAs('public/files', $name);

    $model = new Photo;
    $model->path = $path;
    $model->save();

    $url = config('app.url') . Storage::url($path);

    return Response::pretty(200, 'Success', 'Data has been created', ['data' => $model, 'url' => $url]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\PhotoController  $photoController
   * @return \Illuminate\Http\Response
   */
  public function show(PhotoController $photoController) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\PhotoController  $photoController
   * @return \Illuminate\Http\Response
   */
  public function edit(PhotoController $photoController) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\PhotoController  $photoController
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, PhotoController $photoController) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\PhotoController  $photoController
   * @return \Illuminate\Http\Response
   */
  public function destroy(PhotoController $photoController) {
    //
  }
}
