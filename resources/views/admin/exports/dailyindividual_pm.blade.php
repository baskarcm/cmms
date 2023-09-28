@php
//dd($schedule);
@endphp
<table>
    <thead>
        <tr valign="middle">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="8" style="text-align: center;">{{strtoupper($title)}}</td>
            <td colspan="4" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr>
            <td colspan="2">Date</td>
            <td colspan="3" style="text-align: center;">{{formatDate(Now(),'l, F, d, Y')}}</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Equipment</th>
            <th>Sl.no</th>
            <th colspan="6">Status</th>
            <th>Location</th>
            <th>PIC</th>
            <th colspan="2">Remarks</th>
            <th>Root Cause</th>
        </tr>
    </thead>
    <tbody>
            <tr>
                <td>1.</td>
                <td>{{$schedule->equipment->product->name}}</td>
                <td>{{$schedule->equipment->code}}</td>
                <td colspan="2">
                    @if(!empty($details))
                        @foreach($details as $key => $value)
                            {{++$key}} . {{$value->status}} 
                        @endforeach
                    @endif
                </td>
                <td colspan="2">
                    @if(!empty($details))
                        @foreach($details as $key => $value)
                           @if(!empty($value->action_image))
                                @foreach(json_decode($value->action_image, true) as $key => $action_images)
                                    @php
                                        $actionimages = explode('/', $action_images);
                                        //dd($actionimages);
                                        $action_url = public_path('/'.$actionimages[3].'/'.$actionimages[4].'/'.$actionimages[5].'/'.$actionimages[7]);
                                        echo "<img src ='".$action_url."' width='100' height='75'>"; 
                                    @endphp
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </td>
                <td colspan="2">
                    @if(!empty($details))
                        @foreach($details as $key => $value)
                            @if(!empty($value->defect_image))
                                @foreach(json_decode($value->defect_image, true) as $key => $defect_images)
                                @php
                                        $defectimages = explode('/', $defect_images);
                                        $defect_url = public_path('/'.$defectimages[3].'/'.$defectimages[4].'/'.$defectimages[5].'/'.$defectimages[7]);
                                        echo "<img src ='".$action_url."' width='100' height='75'>"; 
                                    @endphp
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>{{$schedule->equipment->location}}</td>
                <td></td>
                <td colspan="2"></td>
                <td>{{$schedule->root_cause}}</td>
            </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Prepared by:</th>
            <th colspan="2">Checked by:</th>
            <th colspan="2">Acknowledged by:</th>
        </tr>
        <tr>
            <th><b>Name:</b></th>
            <th>{{$schedule->users->name}}</th>
            <th><b>Name:</b></th>
            <th>{{$schedule->schedule->engineer->name}}</th>
            <th><b>Name:</b></th>
            <th>{{$schedule->schedule->manager->name}}</th>
        </tr>
        <tr>
            <th><b>Position:</b></th>
            <th>Technician</th>
            <th><b>Position:</b></th>
            <th>Engineer</th>
            <th><b>Position:</b></th>
            <th>Manager</th>
        </tr>
         <tr>
            <th></th>
            <th>DM Maintenance</th>
            <th></th>
            <th>DM Maintenance</th>
            <th></th>
            <th>MBM PEP</th>
        </tr>
    </tfoot>
</table>
