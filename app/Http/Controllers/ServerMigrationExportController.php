<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerMigrationExportController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = config('services.migration.secret');
        $provided = (string) $request->header('X-Migration-Secret', '');

        if (blank($secret) || ! hash_equals((string) $secret, $provided)) {
            abort(403);
        }

        return response()->json([
            'users' => User::select('id', 'name', 'email', 'password')->get()->makeVisible('password'),
            'main_categories' => MainCategory::all(),
            'sub_categories' => SubCategory::all(),
            'expenses' => Expense::all(),
        ]);
    }
}
