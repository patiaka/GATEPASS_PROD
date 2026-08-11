<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // material_requests.status est un enum (contrainte CHECK sous SQL Server) :
        // on remplace la contrainte pour autoriser la valeur 'Cancelled'.
        // (car_requests.status est une simple colonne string : rien à faire.)
        if (DB::getDriverName() !== 'sqlsrv') {
            return;
        }

        foreach (DB::select("SELECT name, definition FROM sys.check_constraints WHERE parent_object_id = OBJECT_ID('material_requests')") as $c) {
            if (str_contains($c->definition, 'status')) {
                DB::statement("ALTER TABLE material_requests DROP CONSTRAINT [{$c->name}]");
            }
        }

        DB::statement("ALTER TABLE material_requests ADD CONSTRAINT material_requests_status_check CHECK ([status] IN ('Pending','Progress','Rejected','Approved','Expired','Cancelled'))");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlsrv') {
            return;
        }

        foreach (DB::select("SELECT name, definition FROM sys.check_constraints WHERE parent_object_id = OBJECT_ID('material_requests')") as $c) {
            if (str_contains($c->definition, 'status')) {
                DB::statement("ALTER TABLE material_requests DROP CONSTRAINT [{$c->name}]");
            }
        }

        DB::statement("ALTER TABLE material_requests ADD CONSTRAINT material_requests_status_check CHECK ([status] IN ('Pending','Progress','Rejected','Approved','Expired'))");
    }
};
