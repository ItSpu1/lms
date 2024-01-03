<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Payment;
use DateTime;

class ReportController extends Controller
{
    public function ReportView(){

        return view('admin.backend.report.report_view');

    }//end method


    public function SearchByDate(Request $request){

        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $payment = Payment::where('order_date',$formatDate)->latest()->get();
        return view('admin.backend.report.report_by_date',compact('payment','formatDate'));

    }//end method

    public function SearchByMonth(Request $request){

        $month = $request->month;
        $yaer = $request->year_name;

        $payment = Payment::where('order_month',$month)->where('order_year',$yaer)->latest()->get();
        return view('admin.backend.report.report_by_month',compact('payment','month','yaer'));

    }//end method


    public function SearchByYear(Request $request){

        $yaer = $request->year;

        $payment = Payment::where('order_year',$yaer)->latest()->get();
        return view('admin.backend.report.report_by_year',compact('payment','yaer'));

    }//end method
}
