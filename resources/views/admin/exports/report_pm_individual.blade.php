@php
    //dd($schedule);
@endphp
<table>
    <thead>
        <tr valign="middle">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="4" style="text-align: center;">{{strtoupper($title)}}</td>
            <td colspan="2" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr>
            <th colspan="2">Equipment Name :</th>
            <th colspan="2">Equipment Code :</th>
            <th colspan="2">Equipment Location : </th>
            <th colspan="">Document Number :</th>
            <th colspan="">Root Cause :</th>
        </tr>
        <tr>
            <td colspan="2">{{$schedule->equipment->product->name}}</td>
            <td colspan="2">{{$schedule->equipment->code}}</td>
            <td colspan="2">{{$schedule->equipment->location}}</td>
            <td colspan="">{{$schedule->equipment->doc_no}}</td>
            <td colspan="">{{$schedule->root_cause}}</td>
        </tr>

        <tr>
            <th>S.No</th>
            <th>Inspection Point</th>
            <th>Inspection Item</th>
            <th>Judge Std</th>
            <th>Cycle Period</th>
            <th>Status</th>
            <th >Defect Item</th>
            <th >Action Plan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detail as $key => $details)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$details->point->name}}</td>
                <td>{{$details->items->name}}</td>
                <td>{{$details->judge->name}}</td>
                <td>Monthly</td>
                <td>{{$details->status}}</td>
                <td >{{$details->action}} 
                    @if(!empty($details->action_image))
                        @foreach(json_decode($details->action_image, true) as $key => $action_images)
                            @php
                                $actionimages = explode('/', $action_images);
                                //dd($actionimages);
                                $action_url = public_path('/'.$actionimages[3].'/'.$actionimages[4].'/'.$actionimages[5].'/'.$actionimages[7]);
                            @endphp
                            <img src="{{$action_url}}" width="100" height="75">
                        @endforeach
                    @endif
                </td>
                <td >{{$details->defect_item}}
                    @if(!empty($details->defect_image))
                        @foreach(json_decode($details->defect_image, true) as $key => $defect_images)
                        @php
                                $defectimages = explode('/', $defect_images);
                                $defect_url = public_path('/'.$defectimages[3].'/'.$defectimages[4].'/'.$defectimages[5].'/'.$defectimages[7]);
                            @endphp
                            <img src="{{$defect_url}}" width="100" height="75">
                        @endforeach
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"><b>Inspection By:</b></th>
            <th colspan="2"><b>Name and Signature:</b></th>
            <th><b>Date</b></th>
            <th colspan="3"><b>No Abnormalities</b></th>
        </tr>
        <tr>
           <th colspan="2">{{$schedule->users->name}} Technician </th>
            <th colspan="2"></th>
            <th></th>
            <th colspan="3"><b>No Good (N.K) </b></th>
        </tr>
        <tr>
            <th colspan="2"><b>Checked By:</b></th>
            <th colspan="2"><b>Name and Signature:</b></th>
            <th><b>Date</b></th>
            <th colspan="3"><b>Part Change</b></th>
        </tr>
        <tr>
            <th colspan="2">{{$schedule->schedule->engineer->name}} Engineer</th>
            <th colspan="2"></th>
            <th></th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>

