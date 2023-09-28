
@php     
    $planDate = '01-'.$getMonth;
@endphp
<table>
    <thead>
        <tr valign="middle"  style="border: 1px solid #000000;">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="15" style="text-align: center;">PM SCHEDULE {{ strtoupper(formatDate($planDate,'M Y')) }} ACTUAL</td>
            <td colspan="15" style="text-align: center;">DM MANITENANCE</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Product</th>
            <th>Code</th>
            <th>Line</th>
            <th>Station</th>                                    
            @for ($i=1;$i<=$daysCount; $i++)
                <th colspan="2">
                    {{ $i }}
                </th>
            @endfor
        </tr>
        <tr></tr>
    </thead>
    <tbody>        
        @foreach($planvsactual as $key => $value)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $value->equipment->product->name }}</td>
                <td>{{ $value->equipment->code }}</td>                
                <td>{{ $value->equipment->location }}</td>
                <td>{{ $value->equipment->station }}</td>
                @php
                    $month = $getMonth;
                    for($i=1;$i<=$daysCount;$i++)
                    {
                        if(!empty($value->schedule_date))
                        {
                            $checkDate = str_pad($i, 2,'0', STR_PAD_LEFT).'-'.$month;
                            if(strtotime($checkDate)==strtotime(formatDate($value->schedule_date,'d-m-Y')))
                            {
                                echo '<td>#</td>';
                                if(!empty($value->actualScheduledate->date))
                                {
                                    echo '<td>' .formatDate($value->actualScheduledate->date,'d'). '</td>';
                                }
                                else
                                {
                                    echo '<td>-</td>';
                                }
                            }
                            else
                            {
                               echo '<td>-</td>'; 
                               echo '<td>-</td>'; 
                            }
                        }
                        else
                        {
                            echo '<td>-</td>';
                            echo '<td>-</td>';
                        }
                        
                    }
                @endphp
            </tr>
        @endforeach
    </tbody>
</table>