@php
    //dd($detail);
@endphp
<table>
    <thead>
        <tr valign="middle">
            <th colspan="3"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="3" style="text-align: center;">{{strtoupper($title)}}</td>
            <td colspan="2" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr><th colspan="8">Equipment or Instruction</th></tr>
        <tr>
            <th colspan="2">Equipment Name </th>
            <th colspan="2">Location </th>
            <th>Model</th>
            <th>Ref.no</th>
            <th>Finish And Final Line:</th>
            <th>Root Cause</th>
        </tr>
        <tr>
            <td colspan="2">{{$schedule->equipment->product->name}}</td>
            <td colspan="2">{{$schedule->equipment->location}}</td>
            <td>{{$schedule->equipment->code}}</td>
            <td>{{$schedule->schedule->ref_no}}</td>
            <td></td>
            <td>{{$schedule->root_cause}}</td>
        </tr>
        
         <tr>
            <th colspan="2">Failure</th>
            <th colspan="2">Reporting</th>
            <th colspan="2">Maintenance Start</th>
            <th colspan="2">Maintenance End</th>
        </tr>
        <tr>
            <td colspan="">{{$schedule->failure_date}}</td>
            <td colspan="">{{$schedule->failure_time}}</td>
            <td colspan="">{{$schedule->report_date}}</td>
            <td colspan="">{{$schedule->report_time}}</td>
            <td colspan="">{{$schedule->start_date}}</td>
            <td colspan="">{{$schedule->start_time}}</td>
            <td colspan="">{{$schedule->end_date}}</td>
            <td colspan="">{{$schedule->end_time}}</td>
        </tr>
        <tr>
            <td colspan="">Date</td>
            <td colspan="">Time</td>
            <td colspan="">Date</td>
            <td colspan="">Time</td>
            <td colspan="">Date</td>
            <td colspan="">Time</td>
            <td colspan="">Date</td>
            <td colspan="">Time</td>
        </tr>
        <tr>
            <th colspan=""></th>
            <th colspan="2">Request Period</th>
            <th colspan="2">Waiting Period</th>
            <th colspan="2">Maintenance Period</th>
            <th></th>
        </tr>
        <tr>
            <td colspan=""></td>
            <td colspan="2">{{$schedule->request_period}}</td>
            <td colspan="2">{{$schedule->waiting_period}}</td>
            <td colspan="2">{{$schedule->maintenance_period}}</td>
            <td colspan=""></td>
            
        </tr>
         <tr>
            <th colspan="3"></th>
            <th colspan="2">Total Downtime</th>
            <th colspan="3"></th>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="2">{{$schedule->total_downtime}}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th colspan="4">Problem</th>
            <th></th>
            <th colspan="3">Sketch Photo</th>
        </tr>

         <tr>
            <th colspan="4">
                @foreach($detail as $problems)
                    @if(!empty($problems->problem))
                        {{$problems->problem}}
                    @endif
                @endforeach
            </th>
            <th></th>
            <th colspan="3">
                @foreach($detail as $problems)
                    @if(!empty($problems->problem_image) && $problems->type==1)
                        @foreach(json_decode($problems->problem_image, true) as $problem_images)
                            @php
                                $problemimages = explode('/', $problem_images);
                                //dd($actionimages);
                                $problem_url = public_path('/'.$problemimages[3].'/'.$problemimages[4].'/'.$problemimages[5].'/'.$problemimages[7]);
                            @endphp
                            <img src="{{$problem_url}}" alt="" height=100 width=100>
                        @endforeach
                    @endif
                @endforeach
            </th>
        </tr>

        <tr>
            <th colspan="4">Action Taken</th>
            <th></th>
            <th colspan="3">Sketch Photo</th>
        </tr>

         <tr>
            <th colspan="4">
                @foreach($detail as $actions)
                    @if(!empty($actions->action))
                        {{$actions->action}}
                    @endif
                @endforeach
            </th>
            <th></th>
            <th colspan="3">
                @foreach($detail as $actions)
                    @if(!empty($actions->action_image) && $actions->type==2)
                        @foreach(json_decode($actions->action_image, true) as $action_images)
                            @php
                                $actionimages = explode('/', $action_images);
                                //dd($actionimages);
                                $action_url = public_path('/'.$actionimages[3].'/'.$actionimages[4].'/'.$actionimages[5].'/'.$actionimages[7]);
                            @endphp
                            <img src="{{$action_url}}" alt="" height=100 width=100>
                        @endforeach
                    @endif
                @endforeach
            </th>
        </tr>

        <tr>
            <th colspan="4">Prevention Countermeasure</th>
            <th></th>
            <th colspan="3">Sketch Photo</th>
        </tr>

         <tr>
            <th colspan="4">
                @foreach($detail as $preventions)
                    @if(!empty($preventions->prevention))
                        {{$preventions->prevention}}
                    @endif
                @endforeach
            </th>
            <th></th>
            <th colspan="3">
                @foreach($detail as $preventions)
                    @if(!empty($preventions->prevention_image) && $preventions->type==3)
                        @foreach(json_decode($preventions->prevention_image, true) as $prevention_images)
                            @php
                                $preventionimages = explode('/', $prevention_images);
                                //dd($actionimages);
                                $prevention_url = public_path('/'.$preventionimages[3].'/'.$preventionimages[4].'/'.$preventionimages[5].'/'.$preventionimages[7]);
                            @endphp
                            <img src="{{$prevention_url}}" alt="" height=100 width=100>
                        @endforeach
                    @endif
                @endforeach
            </th>
        </tr>
       
    </thead>
    <tfoot>
        <tr>
            <th colspan="3"><b>Prepared By:</b></th>
            <th colspan="2"><b>Verified By:</b></th>
            <th colspan="3"><b>Acknowledged By:</b></th>
        </tr>
        <tr>
            <th colspan="3"><b>Technician</b></th>
            <th colspan="2"><b>Engineer</b></th>
            <th colspan="3"><b>Manager</b></th>
        </tr>
        <tr>
            <th colspan="3">Name: {{$schedule->users->name}}</th>
            <th colspan="2">Name: {{$schedule->schedule->engineer->name}}</th>
            <th colspan="3">Name: {{$schedule->schedule->manager->name}}</th>
        </tr>
        <tr>
            <th colspan="3">Date:</th>
            <th colspan="2">Date:</th>
            <th colspan="3">Date:</th>
        </tr>
    </tfoot>
</table>

