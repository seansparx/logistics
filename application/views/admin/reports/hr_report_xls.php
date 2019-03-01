<html>
    <body>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th> Employee Name </th>
                    <?php
                    for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                        echo '<th><div class="text-center"><b>' . reports_date($d) . '</b></div></th>';
                    }
                    ?>
                    <th>Total Hours</th>
                    <th>Extra Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                    if (count($reports) > 0) {
                        
                        $i = 1;
                        
                        foreach ($reports as $emp_id => $row) {
                       



                              ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $reports[$emp_id]['emp_name'] . ' (' . $reports[$emp_id]['emp_code'] . ')'; ?></td>
                                                    <?php

                                                        $assign_hours = 0;
                                                        $total_hours = 0;
                                                        $extra_hour = 0;

                                                        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                                                            ?>
                                                                <td>
                                                                    <?php
                                                                        if (isset($row[$d])) {

                                                                            echo ($reports[$emp_id][$d]->assign_hours > 0) ? '<span>'.date("H:i", strtotime($reports[$emp_id][$d]->assign_hours)).'</span> hrs.' : '-';
                                                                        } 
                                                                        else {

                                                                            echo '<p class="text-center"> - </p>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                            <?php

                                                            $ah = explode(":", $reports[$emp_id][$d]->assign_hours);

                                                            if($ah[0]) {

                                                                $assign_hours += ((intval($ah[0]) * 60) + intval($ah[1]));
                                                            }
                                                        }
                                                        
                                                        $eh = explode(":", $row['monthly_hours']);
                                                        
                                                        unset($eh[2]);
                                                        
                                                        if($eh[0] > 0){

                                                            $monthly_minuts = ((intval($eh[0]) * 60) + intval($eh[1]));
                                                        }

                                                        $extra = ($assign_hours - $monthly_minuts);
                                                        
                                                        $extra_hrs = ($extra > 0) ? convertToHoursMins($extra).' hrs.' : '-';
                                                    ?>
                                                    <td>
                                                        <b><?php echo ($assign_hours > 0) ? convertToHoursMins($assign_hours).' hrs.' : '-'; ?></b>
                                                    </td>
                                                    <td>
                                                        <b><?php echo $extra_hrs; ?></b>
                                                    </td>
                                            </tr>
                                                <?php



                        }
                    }
                    else {
                        ?>
                        <tr><td colspan="9">No report found</td></tr>    
                        <?php
                    }
                ?>
                <tr><td>&nbsp;</td></tr>    
            </tbody>
        </table>
    </body>
</html>