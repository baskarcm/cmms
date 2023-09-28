
<table>
    <thead>        
        <tr valign="middle">
            <th colspan="2"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
            <td colspan="7" style="text-align: center;">MONTHLY DOWNTIME LIST<br>{{ $selectedYear }}</td>
            <td colspan="5" style="text-align: center;">DM MANITENANCE</td>
        </tr>
        <tr>
            <th>Sl.No</th>
            <th>Month</th>
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
            <!-- <th>Sum</th> -->
        </tr>
    </thead>
    <tbody>        
        <tr>
            <td>1</td>
            <td>Total Production (mins)</td>
            <td>{{ $production['jan'] }}</td>
            <td>{{ $production['feb'] }}</td>
            <td>{{ $production['mar'] }}</td>
            <td>{{ $production['apr'] }}</td>
            <td>{{ $production['may'] }}</td>
            <td>{{ $production['jun'] }}</td>
            <td>{{ $production['jul'] }}</td>
            <td>{{ $production['aug'] }}</td>
            <td>{{ $production['sep'] }}</td>
            <td>{{ $production['oct'] }}</td>
            <td>{{ $production['nov'] }}</td>
            <td>{{ $production['dec'] }}</td>
            <!-- <td>{{ $production['sum'] }}</td> -->
        </tr>   
        <tr>
            <td>2</td>
            <td>Machine DT (mins)</td>
            <td>{{ $total['jan'] }}</td>
            <td>{{ $total['feb'] }}</td>
            <td>{{ $total['mar'] }}</td>
            <td>{{ $total['apr'] }}</td>
            <td>{{ $total['may'] }}</td>
            <td>{{ $total['jun'] }}</td>
            <td>{{ $total['jul'] }}</td>
            <td>{{ $total['aug'] }}</td>
            <td>{{ $total['sep'] }}</td>
            <td>{{ $total['oct'] }}</td>
            <td>{{ $total['nov'] }}</td>
            <td>{{ $total['dec'] }}</td>
            <!-- <td>{{ $total['sum'] }}</td> -->
        </tr> 
        <tr>
            <td>3</td>
            <td>Breakdown Frequency</td>
            <td>{{ $count['jan_count'] }}</td>
            <td>{{ $count['feb_count'] }}</td>
            <td>{{ $count['mar_count'] }}</td>
            <td>{{ $count['apr_count'] }}</td>
            <td>{{ $count['may_count'] }}</td>
            <td>{{ $count['jun_count'] }}</td>
            <td>{{ $count['jul_count'] }}</td>
            <td>{{ $count['aug_count'] }}</td>
            <td>{{ $count['sep_count'] }}</td>
            <td>{{ $count['oct_count'] }}</td>
            <td>{{ $count['nov_count'] }}</td>
            <td>{{ $count['dec_count'] }}</td>
            <!-- <td>-</td> -->
        </tr>
        <tr>
            <td>4</td>
            <td>MTBF</td>
            <td>{{ $mtbf['jan'] }}</td>
            <td>{{ $mtbf['feb'] }}</td>
            <td>{{ $mtbf['mar'] }}</td>
            <td>{{ $mtbf['apr'] }}</td>
            <td>{{ $mtbf['may'] }}</td>
            <td>{{ $mtbf['jun'] }}</td>
            <td>{{ $mtbf['jul'] }}</td>
            <td>{{ $mtbf['aug'] }}</td>
            <td>{{ $mtbf['sep'] }}</td>
            <td>{{ $mtbf['oct'] }}</td>
            <td>{{ $mtbf['nov'] }}</td>
            <td>{{ $mtbf['dec'] }}</td>
            <!-- <td>-</td> -->
        </tr> 
        <tr>
            <td>5</td>
            <td>MTTR</td>
            <td>{{ $mttr['jan'] }}</td>
            <td>{{ $mttr['feb'] }}</td>
            <td>{{ $mttr['mar'] }}</td>
            <td>{{ $mttr['apr'] }}</td>
            <td>{{ $mttr['may'] }}</td>
            <td>{{ $mttr['jun'] }}</td>
            <td>{{ $mttr['jul'] }}</td>
            <td>{{ $mttr['aug'] }}</td>
            <td>{{ $mttr['sep'] }}</td>
            <td>{{ $mttr['oct'] }}</td>
            <td>{{ $mttr['nov'] }}</td>
            <td>{{ $mttr['dec'] }}</td>
            <!-- <td>-</td> -->
        </tr>
        <tr>
            <td>6</td>
            <td>Machine Uptime in %</td>
            <td>{{ $per['jan'] }}</td>
            <td>{{ $per['feb'] }}</td>
            <td>{{ $per['mar'] }}</td>
            <td>{{ $per['apr'] }}</td>
            <td>{{ $per['may'] }}</td>
            <td>{{ $per['jun'] }}</td>
            <td>{{ $per['jul'] }}</td>
            <td>{{ $per['aug'] }}</td>
            <td>{{ $per['sep'] }}</td>
            <td>{{ $per['oct'] }}</td>
            <td>{{ $per['nov'] }}</td>
            <td>{{ $per['dec'] }}</td>
            <!-- <td>-</td> -->
        </tr> 
        <tr>
            <td>7</td>
            <td>Target</td>
            <td>{{ $target['jan'] }}</td>
            <td>{{ $target['feb'] }}</td>
            <td>{{ $target['mar'] }}</td>
            <td>{{ $target['apr'] }}</td>
            <td>{{ $target['may'] }}</td>
            <td>{{ $target['jun'] }}</td>
            <td>{{ $target['jul'] }}</td>
            <td>{{ $target['aug'] }}</td>
            <td>{{ $target['sep'] }}</td>
            <td>{{ $target['oct'] }}</td>
            <td>{{ $target['nov'] }}</td>
            <td>{{ $target['dec'] }}</td>
            <!-- <td>-</td> -->
        </tr>           
    </tbody>
</table>