<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>會員登入</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <?php
                    echo $this->Form->create('Member', array('action' => 'login'));
                    echo $this->Form->input('username');
                    echo $this->Form->input('password');
                    echo $this->Form->end('登入');
                    ?>
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->