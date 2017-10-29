<div class="panel-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tasks['ResultObj'] as $task) { ?>
                        <tr>
                            <td><?php echo $task->id?></td>
                            <td><?php echo $task->name?></td>
                            <td><?php echo $task->start?></td>
                            <td><?php echo $task->assignee->name?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.col-lg-12 (nested) -->
    </div>
    <!-- /.row -->
</div>
<!-- /.panel-body -->