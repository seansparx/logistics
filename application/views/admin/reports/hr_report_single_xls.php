<html>
    <body>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th> Employee </th>
                    <th> Date </th>
                    <th> Assign Time </th>
                    <th> Working Time </th>
                    <th> Extra Time </th>
                    <th> Remarks </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (count($reports) > 0) {

                        $i = 1;

                        foreach ($reports as $obj) {
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $emp_name; ?></td>
                                    <td><?php echo display_date($obj->entry_date); ?></td>
                                    <td><?php echo date("H:i", strtotime($obj->assign_hours)); ?> hrs</td>
                                    <td><?php echo ($obj->total_hours > 0) ? date("H:i", strtotime($obj->total_hours)).' hrs' : '__:__'; ?></td>
                                    <td><?php echo ($obj->extra_hour > 0) ? date("H:i", strtotime($obj->extra_hour)).' hrs' : '__:__'; ?></td>
                                    <td><?php echo $obj->remarks; ?></td>
                                </tr>
                            <?php
                        }
                    } 
                    else {
                        ?>
                            <tr><td colspan="6"><center>No report found</center></td></tr>
                        <?php
                    }
                ?>
                <tr><td>&nbsp;</td></tr>    
            </tbody>
        </table>
    </body>
</html>