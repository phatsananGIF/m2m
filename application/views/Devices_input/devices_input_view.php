<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M2M</title>

<style>
    table {
    border-collapse: collapse;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
</style>
</head>
<body>

    <h1>Devices_input</h1>

    <table>
        <thead>
            <tr>
                <th>serial</th>
                <th>coin</th>
                <th>status</th>
                <th>temp</th>
                <th>timestam</th>
                <th>date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result_devices_input as $key=>$val){?>
                <tr>
                    <td><?php echo $val['serial'];?></td>
                    <td><?php echo $val['coin'];?></td>
                    <td><?php echo $val['status'];?></td>
                    <td><?php echo $val['temp'];?></td>
                    <td><?php echo $val['num_timestam'];?></td>
                    <td><?php echo $val['updated'];?></td>
                </tr>
            <?php }?>
        </tbody>    
    </table>
    
</body>
</html>