<html>
    <body>
        <table>
            <thead>
                <tr>
                    <th> # </th>
                    <th> Code </th>
                    <th> Employee Name </th>                                        
                    <?php
                    foreach ($projects as $proj_obj) {

                        if ($proj_obj->status == 'active') {
                            ?>
                            <th><?php echo $proj_obj->code; ?></th>   
                            <?php
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($employees) && count($employees) > 0) {
                    $i = 1;
                    foreach ($employees as $emp_obj) {

                        if ($emp_obj->status == 'active') {
                            ?>
                            <tr>
                                <td width="30"><?php echo $i++; ?></td>
                                <td width="80"><?php echo emp_code($emp_obj->id); ?></td>
                                <td width="200"><?php echo $emp_obj->emp_name; ?></td>
                                <?php
                                foreach ($projects as $proj_obj) {

                                    if ($proj_obj->status == 'active') {
                                        ?>
                                        <td>
                                            <?php
                                            $total_hrs = array_sum($reports[$emp_obj->id][$proj_obj->id]['assignd_hrs']);
                                            $consumed_hrs = array_sum($reports[$emp_obj->id][$proj_obj->id]['consumed_hrs']);
                                            echo round((($consumed_hrs * 100) / $total_hrs), 2) . '%';
                                            ?>
                                        </td>   
                                        <?php
                                    }
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <tr><td colspan="9">No report found</td></tr>    
                    <?php
                }
                ?>  
            </tbody>
        </table>
    </body>
</html>