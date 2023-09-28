
<table>
    <thead>
        <tr valign="middle">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="6" style="text-align: center;">INVENTORY LIST</td>
            <td colspan="2" style="text-align: center;">DM MAINTENANCE</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Equipment</th>
            <th>Title</th>
            <th>Spare Name</th>
            <th>Sl.no</th>
            <th>Line</th>
            <th>Station</th>
            <th>Technician</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($inventory as $key => $value)
            <tr>
                <th align="left">{{ ++$key }}</th>
                <td>{{ $value->equipment->product->name }}</td>
                <th>{{ $value->title }}</th>
                <th>{{ $value->name }}</th>
                <td align="left">{{ $value->equipment->code }}</td>
                <td align="left">{{ $value->equipment->location }}</td>
                <td align="left">{{ $value->equipment->station }}</td>
                <td>{{ $value->users->name }}</td>
                <td>{{ formatDate($value->date,'d-m-Y') }}</td>
                @if($value->active==0)
                    <td>Not Available</td>
                @elseif($value->active==1)
                    <td>Available</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>