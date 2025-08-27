<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\Document;
use Illuminate\Http\Request;

use function compact;
use function view;

final class DocumentController extends Controller
{
    use DeleteAction;

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return view('document.update', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate(['image' => 'required|file|mimes:png,jpg']);
        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = $file->hashName();
            $chemin = $file->storeAs('material/document', $filename, 'public');
            $document->update(['chemin' => $chemin]);
        }

        flash('document updated with success!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $document)
    {
        $delete = Document::findOrFail($document);
        $this->file_delete($delete);

        return $this->supp($delete);
    }
}
