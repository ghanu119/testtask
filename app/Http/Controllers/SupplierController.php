<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierCreateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(file_get_contents(resource_path('data/suppliers.json')),200, [
            'Content-Type' => 'application/json'
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierCreateRequest $request)
    {
        \Log::info('HERE in REQUEST');
        $supplier = new Supplier();
        $supplier->name = $request->get('name');
        $supplier->info = $request->get('info');
        $supplier->rules = $request->get('rules');
        $supplier->district = $request->get('district');
        $supplier->url = $request->get('url');
        $supplier->save();

        return response()->json( [ 'success'=>'true', 'message'=>'Supplier created successfullt'], 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        //
    }

    /**
     * Calculate suppliers hours 
     * 
     */
    public function calc_hours ( $suppliers ){
        $hours = 0;
        foreach( $suppliers as $s ){
            $working = Arr::only( $s, ['mon','tue', 'wed','thu', 'fri', 'sat','sun']);
            foreach( $working as $w ){
                $w = strstr( $w, ': '  );
                $w = str_replace(": ", "", $w);
                $hourData = explode(',', $w);
                if( count( $hourData ) ){
                    foreach( $hourData as $hD ){
                        
                        $h = $this->getHoursDiff( $hD);
                        if( $h > 0 ){
                            $hours += $h;
                        }
                    }
                }
            } 

        }
        return $hours;
    }

    /**
     * Get tthe hours different betweek two hours
     * 
     */
    private function getHoursDiff( $hours ){
        $h = explode( '-', $hours);
        $fH = Carbon::createFromFormat( 'H:i', $h[0]);
        $sH = Carbon::createFromFormat( 'H:i', $h[1]);
        return $fH->diffInHours( $sH);
    }
}
