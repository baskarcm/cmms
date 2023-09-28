
<table>
    <thead>        
        <tr valign="middle">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="7" style="text-align: center;">MACHINE DOWNTIME LIST</td>
            <td colspan="6" style="text-align: center;">DM MANITENANCE</td>
        </tr>
        <tr>
            <th>Station</th>
            <th>Machine Name</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>May</th>
            <th>Jun</th>
            <th>July</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Oct</th>
            <th>Nov</th>
            <th>Dec</th>
            <th>Sum</th>
        </tr>
    </thead>
    <tbody>        
        @foreach($reports as $report)
            <tr>
                <td align="left">{{ $report['station'] }}</td>
                <td>{{ $report['name'] }}</td>
                <td>{{ $report['Jan'] }}</td>
                <td>{{ $report['Feb'] }}</td>
                <td>{{ $report['Mar'] }}</td>
                <td>{{ $report['Apr'] }}</td>
                <td>{{ $report['May'] }}</td>
                <td>{{ $report['Jun'] }}</td>
                <td>{{ $report['July'] }}</td>
                <td>{{ $report['Aug'] }}</td>
                <td>{{ $report['Sep'] }}</td>
                <td>{{ $report['Oct'] }}</td>
                <td>{{ $report['Nov'] }}</td>
                <td>{{ $report['Dec'] }}</td>
                <td>{{ $report['sum'] }}</td>
            </tr>
        @endforeach
            <tr>
                <td colspan="2">TOTAL</td>
                <td>{{ $totals['jan'] }}</td>
                <td>{{ $totals['feb'] }}</td>
                <td>{{ $totals['mar'] }}</td>
                <td>{{ $totals['apr'] }}</td>
                <td>{{ $totals['may'] }}</td>
                <td>{{ $totals['jun'] }}</td>
                <td>{{ $totals['jul'] }}</td>
                <td>{{ $totals['aug'] }}</td>
                <td>{{ $totals['sep'] }}</td>
                <td>{{ $totals['oct'] }}</td>
                <td>{{ $totals['nov'] }}</td>
                <td>{{ $totals['dec'] }}</td>
                <td>{{ $totals['sum'] }}</td>
            </tr>
            <!-- <tr>
                <td colspan="2">TARGET</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ '###' }}</td>
                <td>{{ '###' }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
                <td>{{ 92.00 }}</td>
            </tr> -->
    </tbody>
</table>
