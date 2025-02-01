<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;

class StatisticsController extends Controller
{
    public function getStatistics(Request $request)
    {
        return response()->json([
            'response' => 'get statistics',
            'data' => 'statistics',
        ], 200);
    }
}

