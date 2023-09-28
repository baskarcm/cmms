
<table>
    <thead>
        
    <tr valign="middle">
        <th colspan="3"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
        <td colspan="5" style="text-align: center;">SCHEDULE LIST</td>
        <td colspan="3" style="text-align: center;">DM MAINTENANCE</td>
    </tr>
    <tr>
        <th>S.No</th>
        <th>Equipment</th>
        <th>Sl.no</th>
        <th>Line</th>
        <th>Station</th>
        <th>Technician</th>
        <th>Engineer</th>
        <th>Manager</th>
        <th>Schedule Status</th>
        <th>Maintenance Type</th>
        <th>Schedule Date</th>
    </tr>
    </thead>
    <tbody>
        
        @foreach($schedule as $key => $value)
            <tr>
                <th>{{ ++$key }}</th>
                <td>{{ $value->equipment->product->name }}</td>
                <td>{{ $value->equipment->code }}</td>
                <td>{{ $value->equipment->location }}</td>
                <td>{{ $value->equipment->station }}</td>
                <td>{{ !empty($value->users->name) ? $value->users->name : 'Not Defind'  }}</td>
                <td>{{ !empty($value->engineer->name) ? $value->engineer->name : 'Not Defind' }}</td>
                <td>{{ !empty($value->manager->name) ? $value->manager->name : 'Not Defind' }}</td>
                @if($value->schedule_status==0)
                    <td>Schedule</td>
                @elseif($value->schedule_status==1)
                    <td>Pending</td>
                @elseif($value->schedule_status==2)
                    <td>Completed</td>
                @endif
                
                <td>{{ $value->moduleType->name }}</td>
                <td>{{ formatDate($value->schedule_date,'d-m-Y') }}</td>
            </tr>
        @endforeach
      
    </tbody>
</table>