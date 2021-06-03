<?php

namespace App\Http\Controllers;

use App\Models\ReportComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
        // $req = $request->all();
        $report = new ReportComments;
        $report->reason_for_report = $_REQUEST['reason_for_report'];
        $report->comment_id = $_REQUEST['comment_id'];
        $report->user_making_report = Auth::user()->id;
        $res = $report->save();
        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportComments  $reportComments
     * @return \Illuminate\Http\Response
     */
    public function show(ReportComments $reportComments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportComments  $reportComments
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportComments $reportComments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportComments  $reportComments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportComments $reportComments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportComments  $reportComments
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportComments $reportComments)
    {
        //
    }
}
