<html>
    <body>
        <?php

            if (count($reports) > 0) {

                $i = 1;

                if ($report_type == 'vehicle') {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th> #ID </th>
                                <th> Vehicle </th>
                                <th>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th> Contract Title </th>
                                                <th> Service Title </th>
                                                <th> Department </th>
                                                <th> Assign Hours </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($reports as $vhl_id => $arr) {

                                    ?>
                                        <tr>
                                            <td><?php echo code($vhl_id); ?></td>
                                            <td><?php echo vehicle_name($vhl_id); ?></td>
                                            <td>
                                                <table>
                                                    <tbody>
                                                        <?php                                                                             
                                                            foreach ($arr as $obj) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $obj->project_name; ?> </td>
                                                                    <td><?php echo $obj->service_title; ?></td>
                                                                    <td><?php echo $obj->department_name; ?></td>
                                                                    <td><?php echo convertToHoursMins($obj->slots * 30); ?> hrs</td>
                                                                </tr>    
                                                                <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>    
                                    <?php


                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } 
                else if ($report_type == 'employee') {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th> #ID </th>
                                <th> Employee Name </th>
                                <th>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th> Contract Title </th>
                                                <th> Service Title </th>
                                                <th> Department </th>
                                                <th> Assign Hours </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($reports as $emp_id => $arr) {

                                    ?>
                                        <tr>
                                            <td><?php echo emp_code($emp_id); ?></td>
                                            <td><?php echo emp_name($emp_id); ?></td>
                                            <td>
                                                <table>
                                                    <tbody>
                                                        <?php 
                                                            foreach ($arr as $obj) {
                                                                ?>

                                                                    <tr>
                                                                        <td><?php echo $obj->project_name; ?> </td>
                                                                        <td><?php echo $obj->service_title; ?></td>
                                                                        <td><?php echo $obj->department_name; ?></td>
                                                                        <td><?php echo convertToHoursMins($obj->slots * 30); ?> hrs</td>
                                                                    </tr>

                                                                <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>    
                                    <?php


                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } 
                else {
                    ?>
                        <table>
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Contract Title </th>
                                    <th> Assigned Resources </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($reports as $obj) {
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $obj->project; ?></td>
                                                <td>
                                                    <table>
                                                        <tbody>
                                                            <?php                                                                             
                                                                if(count($obj->assign_employees) > 0){

                                                                    ?>
                                                                        <tr>
                                                                            <th>Employee</th>
                                                                            <th>Service</th>
                                                                            <th>Department</th>
                                                                            <th>Assign hours</th>
                                                                        </tr>    
                                                                    <?php

                                                                    foreach($obj->assign_employees as $emp) {

                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $emp->emp_name.' ('.emp_code($emp->resource_id).')'; ?></td>
                                                                                <td><?php echo $emp->service_title; ?></td>
                                                                                <td><?php echo $emp->department_name; ?></td>
                                                                                <td><?php echo ($emp->timings ? $emp->timings.' hrs' : '__:__'); ?></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                            <tr height="10"><td colspan="5"></td></tr>
                                                            <?php 
                                                                if(count($obj->assign_vehicles) > 0) {

                                                                    ?>
                                                                        <tr>
                                                                            <th>Vehicle</th>
                                                                            <th></th>                                                                            
                                                                            <th></th>
                                                                            <th></th>
                                                                        </tr>    
                                                                    <?php
                                                                    foreach($obj->assign_vehicles as $vhl) {

                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $vhl->regn_number.' ('.$vhl->model.')'; ?></td>
                                                                                <td><?php echo $vhl->service_title; ?></td>
                                                                                <td><?php echo $vhl->department_name; ?></td>
                                                                                <td><?php echo ($vhl->timings ? $vhl->timings.' hrs' : '__:__'); ?></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                        <?php                                                    
                                    }
                                ?>
                            </tbody>
                        </table>
                    <?php
                }
            } 
            else {
                ?>
                <p>No report found</p>
                <?php
            }
        ?>
    </body>
</html>