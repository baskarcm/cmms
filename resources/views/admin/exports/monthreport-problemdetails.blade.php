@php
    $totalBreakdown =0;
    $totalDownTime =0;
@endphp
<table>
    <thead>
        
    <tr valign="middle">
        <th colspan="3"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
        <td colspan="4" style="text-align: center;">MONTH REPORT PROBLEM DETAILS {{$month}}</td>
        <td colspan="3" style="text-align: center;">DM MANITENANCE</td>
    </tr>
    <tr>
        <th>S.No</th>
        <th colspan="2">Equipment</th>
        <th colspan="2">Problems</th>
        <th>B Down</th>
        <th>P Down</th>
        <th colspan="2">Root Cause (RC) and Action Plan (AP)</th>
        <th >MR Status</th>
    </tr>
    </thead>
    <tbody>
        @foreach($schedule as $key => $value)
            <tr>
                <td>{{ ++$key }}</td>
                <td colspan="2">{{ $value->equipment->product->name }}</td>
                <td colspan="2">{{ $value->problemdetail['problem'] }}</td>
                <td>{{ $value->total }}</td>
                <td>{{ $value->problemdetail['pdown'] }}</td>
                <td colspan="2">{{ $value->problemdetail['route_cause'] }}</td>
                <td>{{ $value->problemdetail['mr_status'] }}</td>
                @php
                    $totalBreakdown +=$value->total;
                    $totalDownTime +=$value->problemdetail['pdown'];
                @endphp
            </tr>
        @endforeach
            <tr>
                <td colspan="5">TOTAL BREAKDOWN (MIN)</td>
                <td colspan="5">{{$totalBreakdown}}</td>
            </tr>
            <tr>
                <td colspan="5">TOTAL DOWNTIME TO PRODUCTION (MIN)</td>
                <td colspan="5">{{$totalDownTime}}</td>
            </tr>
    </tbody>
</table>