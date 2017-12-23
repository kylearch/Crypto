<?php

namespace App\Http\Controllers;

use App\Helpers\PriceHelper;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $balances = Auth::user()->balances()->select(['coin_id', 'price', DB::raw('SUM(`balance`) AS `balance`')])->groupBy('coin_id')->get()->keyBy('coin.symbol');
        $balances = Auth::user()->balances()->groupBy('coin_id')->selectRaw('coin_id, SUM(price) as price, SUM(balance) as balance')->get()->keyBy('coin.symbol');

        PriceHelper::getPrices($balances);

        $value = $balances->sum('value');
        $gain  = $balances->sum('gain');

        return view('balances.index', compact('balances', 'value', 'gain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
