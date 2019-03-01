
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
                                                ?>
                                                <td>
                                                    <?php
                                                        if(isset($reports[$emp_id][$d])){

                                                            foreach ($reports[$emp_id][$d] as $report) {

                                                                
                                                                echo '<b>' . $report->project_name . '</b></br>';
                                                                echo 'Assign Hrs : ' . convertToHoursMins(($report->slots * 30)) . '</br>';
                                                                
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
                    } 
                    else {
                        ?>
                        <tr><td colspan="9">No report found</td></tr>    
                        <?php
                    }
                ?>  
            </tbody>
        </table>        
    </body>
</html>