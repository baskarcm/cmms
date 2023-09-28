
<table>
    <thead>
        <tr valign="middle">
            <th colspan="4"><img src="{{public_path('/common/img/logo/logo-small.png')}}" alt="" height=100 width=100></th>
        </tr>
        <tr valign="middle">
            <td colspan="4" style="text-align: center;">INVENTORY STOCK LIST</td>
        </tr>
        <tr valign="middle">
            <td colspan="4" style="text-align: center;">DM MANITENANCE</td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Code</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>        
        @foreach($inventory_stock as $key => $value)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->code }}</td>
                <td>{{ $value->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
