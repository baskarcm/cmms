
<table>
    <thead>
        <tr valign="middle">
            <th colspan="3"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="5" style="text-align: center;">{{strtoupper($title)}}</td>
            <td colspan="2" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Equipment</th>
            <th>Sl.no</th>
            <th>Line</th>
            <th>Station</th>
            <th>Technician</th>
            <th>Engineer</th>
            <th>Approval Status</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($schedule as $key => $value)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $value->equipment->product->name }}</td>
                <td>{{ $value->equipment->code }}</td>
                <td>{{ $value->equipment->location }}</td>
                <td>{{ $value->equipment->station }}</td>
                <td>{{ $value->users->name }}</td>
                <td>{{ $value->schedule->engineer->name }}</td>
                @if($value->schedule->engineer_status==0)
                    <td>Pending</td>
                @elseif($value->schedule->engineer_status==1)
                    <td>Reject</td>
                @elseif($value->schedule->engineer_status==2)
                    <td>Completed</td>
                @endif
                
                <td>{{ formatDate($value->date,'d-m-Y') }}</td>
                @if($value->active==0)
                    <td>Inactive</td>
                @elseif($value->active==1)
                    <td>Active</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>