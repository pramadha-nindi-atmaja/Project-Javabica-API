@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="margin-bottom:30px;">
            <div class="col-lg-12">
                selmat datang kembali :{{Auth::user()->name}}<span> | </span>(<a  href="{{url('system-app/logout')}}">logout</a>)
            </div>
           
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul>              
                    <li><a target="_blank" href="{{url('system-app/api-documentation')}}">api documentation (support in chrome browser)</a> </li>
                    <li><a  target="_blank"  href="{{url('system-app/log-monitor')}}">application log viewer</a></li>
                    <li><a  target="_blank"  href="{{url('system-app/permissions')}}">role and permission</a></li>
                </ul>
            </div>  
        </div>
    </div>
   
@endsection
