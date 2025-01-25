<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\Cours;
use App\Models\Devoir;
use App\Models\Document;
use App\Models\Folder;
use App\Models\Journal;
use App\Models\MaterialRequest;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

trait DeleteAction
{
    // public function journal(string $action): void
    // {
    //     Journal::create([
    //         'user_id' => Auth::user()->id,
    //         'structure_id' => Auth::user()->structure(),
    //         'libelle' => $action,
    //     ]);
    // }

    public function supp(Model $delete): JsonResponse
    {
        $delete->delete();

        return response()->json([
            'success' => true,
            'message' => $delete ? class_basename($delete) . ' deleted with success ' : class_basename($delete) . ' not found',
        ]);
    }

    public function file_delete(Model $model): bool
    {
        $fileDeleted = false;
        if (File::exists(public_path($model->DocLink()))) {
            $fileDeleted = File::delete(public_path($model->DocLink()));
        }

        return $fileDeleted;
    }

    public function file_uplode($request, MaterialRequest $model): void
    {


        try {
            foreach ($request as $key => $file) {
                $filename = $file->hashName();
                $chemin = $file->storeAs('material/document', $filename, 'public');
                Document::create([
                    'material_request_id' => $model->id,
                    'chemin' => $chemin,
                ]);
            }
        } catch (\Throwable $th) {
            new Exception('file uplode error');
        }
    }
}
