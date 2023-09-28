@php
    $i=0;
    $j=0;
@endphp
<table>
    <thead>
        <tr valign="middle">
            <th colspan="3"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="13" style="text-align: center;">{{strtoupper($title)}}</td>
            <td colspan="3" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr>
            <td colspan="3">Date</td>
            <td colspan="4" style="text-align: center;">{{formatDate(Now(),'l, F, d, Y')}}</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Equipment</th>
            <th>Serial No</th>
            <th>Location</th>
            <th colspan="4">Problem</th>
            <th colspan="4">Countermeasure</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Total Downtime</th>
            <th>Total Downtime to Prod</th>
            <th>PIC</th>
            <th>Status</th>
            <th>Ref.No</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1.</td>
            <td>{{$schedule->equipment->product->name}}</td>
            <td>{{$schedule->equipment->code}}</td>
            <td>{{$schedule->equipment->location}}</td>
            <td colspan="2">
                 @if(!empty($details))
                    @foreach($details as $key => $value)
                        {{++$i}} . {{$value->problem}} 
                    @endforeach
                @endif
            </td>
            <td colspan="2">
                 @if(!empty($details))
                    @foreach($details as $problems)
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
                @endif
            </td>
            <td colspan="2">
                @if(!empty($details))
                    @foreach($details as $key => $value)
                        @if($value->type==3)
                            {{++$j}} . {{$value->prevention}} 
                        @endif
                    @endforeach
                @endif
            </td>
            <td colspan="2">
                 @if(!empty($details))
                    @foreach($details as $preventions)
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
                @endif
            </td>
            <td>{{$schedule->start_time}}</td>
            <td>{{$schedule->end_time}}</td>
            <td>{{$schedule->total_downtime}}</td>
            <td></td>
            <td></td>
            <td>
                @if($schedule->manager_status==1)
                    Reject
                @elseif($schedule->manager_status==2)
                    Completed
                @else
                    Pending
                @endif
            </td>
            <td>{{$schedule->schedule->ref_no}}</td>
        </tr>
        
    </tbody>
     <tfoot>
        
        <tr>
            <th colspan="4">Prepared by:</th>
            <th></th>
            <th colspan="4">Checked by:</th>
            <th></th>
            <th colspan="4">Acknowledged by:</th>
            <th></th>
        </tr>
        <tr>
            <th><b>Name:</b></th>
            <th colspan="3">{{$schedule->users->name}}</th>
            <th></th>
            <th><b>Name:</b></th>
            <th colspan="3">{{$schedule->schedule->engineer->name}}</th>
            <th></th>
            <th><b>Name:</b></th>
            <th colspan="3">{{$schedule->schedule->manager->name}}</th>
            <th></th>
        </tr>
        <tr>
            <th><b>Position:</b></th>
            <th colspan="3">Technician</th>
            <th></th>
            <th><b>Position:</b></th>
            <th colspan="3">Engineer</th>
            <th></th>
            <th><b>Position:</b></th>
            <th colspan="3">Manager</th>
            <th></th>
        </tr>
         <tr>
            <th></th>
            <th colspan="3">DM Maintenance</th>
            <th></th>
            <th></th>
            <th colspan="3">DM Maintenance</th>
            <th></th>
            <th></th>
            <th colspan="3">MBM PEP</th>
            <th></th>
        </tr>
    </tfoot>
</table>
