<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\Http\Request;
use App\Http\Requests\TextsRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateTextRequest;

class TextController extends Controller
{
  /**
   * Get texts from DB
   *
   * @return void
   */
  public function get(Request $req)
  {
    $texts = Text::where('text', 'like', "%{$req->search}%")->orWhere('title', 'like', "%{$req->search}%")->get();
    return $texts;
  }

  /**
   * Save text to DB
   *
   * @param TextsRequest $request
   * @return void
   */
  public function store(TextsRequest $request)
  {
    $file = $request->getFile();
    $file->storeAs('public/texts_images', $file->getClientOriginalName());

    Text::create([
      'text'     => $request->getText(),
      'url'      => $file->getClientOriginalName(),
      'title'    => $request->getTitle(),
      'doctor'   => $request->getDoctor(),
      'location' => $request->getLocation(),
    ]);
  }

  /**
   * Update text from DB
   *
   * @param UpdateTextRequest $request
   * @param Text $text
   * @return void
   */
  public function update(UpdateTextRequest $request, Text $text)
  {
    if ($request->getFile() != null) {
      Storage::disk('public')->delete('texts_images/' . $text->url);
      $file = $request->getFile();
      $file->storeAs('public/texts_images', $file->getClientOriginalName());

      $text->url = $file->getClientOriginalName();
    }

    if ($request->getText() != null)
      $text->text = $request->getText();

    if ($request->getTitle() != null)
      $text->title = $request->getTitle();

    if ($request->getDoctor() != null)
      $text->doctor = $request->getDoctor();

    if ($request->getLocation() != null)
      $text->location = $request->getLocation();

    $text->save();
  }

  /**
   * Deletes a text
   *
   * @param Text $text
   * @return void
   */
  public function delete(Text $text)
  {
    Storage::disk('public')->delete('texts_images/' . $text->url);
    $text->delete();
  }
}
