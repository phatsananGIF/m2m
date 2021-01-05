<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M2M</title>

<style>
    table {
    border-collapse: collapse;
    margin-top: 20px;
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

    <h1>Count Devices_input</h1>

    <?php echo form_open(base_url().'devices_input/view_count_data_input');?>
        <label for="start_date">Start date :</label>
        <input type="datetime-local" id="start_date" name="start_date" value="<?= $start_date ?>">

        <label for="end_date">End date:</label>
        <input type="datetime-local" id="end_date" name="end_date" value="<?= $end_date ?>">

        <button type="submit" name="btsearch" value="Search" >Search</button>
        <button type="submit" name="btsearch" value="Delete" >Delete</button>
    <?php echo form_close();?>



    <table id='count_table'>
        <thead>
            <tr>
                <th>serial</th>
                <th>count coin</th>
                <th>status</th>
                <th>temp</th>
                <th>timestam</th>
                <th>date</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach($device_data as $key=>$data){ ?>
            <tr>
                <td><a href="<?php echo base_url(); ?>devices_input/view_data_input/<?= $data['serial']?>/<?= $start_date ?>/<?= $end_date ?>"><?= $data['serial'] ?></a> </td>
                <td><?= $data['coin'] ?></td>
                <td><?= $data['status'] ?></td>
                <td><?= $data['temp'] ?></td>
                <td><?= $data['num_timestam'] ?></td>
                <td><?= $data['updated'] ?></td>
                <td> 
                    <button onclick="window.location.href = '<?php echo base_url(); ?>devices_input/view_count_data_input/<?= $data['serial']?>/<?= $start_date ?>/<?= $end_date ?>';" >delete</button>
                </td>
            </tr>
        <?php } ?>
            
        </tbody>
    </table>
    
</body>
</html>