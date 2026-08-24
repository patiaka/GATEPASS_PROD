<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Reports\OffsiteReportData;
use Illuminate\Http\Request;

/**
 * Version imprimable du rapport « Offsite Records ».
 *
 * Le document est rendu par le navigateur de l'utilisateur, qui l'enregistre
 * en PDF via sa boîte de dialogue d'impression. Contrairement à l'export
 * Browsershot, ce chemin ne dépend ni de Node, ni de Chrome, ni des droits du
 * pool d'applications IIS sur le serveur.
 */
final class OffsitePrintController extends Controller
{
    public function __invoke(Request $request)
    {
        $report = OffsiteReportData::fromFilters($request->query());

        return view('reports.offsite-pdf', $report->documentData(print: true));
    }
}
