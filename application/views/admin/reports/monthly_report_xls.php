
<?php

//pr($reports);

?>
<html>
    <body>
    <table>
        <thead>
            <tr>
                <th> # </th>
                <th> <?php echo ($report_type == 'vehicle') ? 'Vehicle' : 'Employee' ?> Name </th>
                <?php
                for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                    echo '<th><div class="text-center"><b>' . reports_date($d) . '</b></div></th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php

            if (isset($reports) && count($reports) > 0) {
                $i = 1;
                foreach ($reports as $emp_id => $row) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $reports[$emp_id]['emp_name'] . ' (' . $reports[$emp_id]['emp_code'] . ')'; ?></td>
                        <?php
                        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                            //pr($reports[$emp_id][$d][0]);
                            ?>
                            <td style="width:50% !important;">

                                <?php

                                // if (isset($reports[$emp_id][$d]->project_name)) {
                                //     echo '<ul style="width:120px;">';
                                //     echo '<li><b>' . $reports[$emp_id][$d]->project_name . '</b></li>';
                                //     echo '<li>Start Time : ' . display_time($reports[$emp_id][$d]->start_time) . '</li>';
                                //     echo '<li>End Time : ' . display_time($reports[$emp_id][$d]->end_time) . '</li>';
                                //     echo '</ul>';
                                // } else {
                                //     echo '<ul><li><p class="text-center">---</p></li></ul>';
                                // }

                                  
                                    if(isset($reports[$emp_id][$d])){

                                        foreach ($reports[$emp_id][$d] as $report) {

                                            //pr($report);

                                            echo '<ul style="width:120px;">';
                                            echo '<li><b>' . $report->project_name . '</b></li>';
                                            echo '<li>Assign Hrs : ' . convertToHoursMins(($report->slots * 30)) . '</li>';
                                            echo '</ul>';
                                        }
                                    }
                                                                             


                                ?>

                            </td>
                            <?php
                        }
                        ?>
                    </tr>    
                    <?php
                }

                //die('########');
            } else {
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