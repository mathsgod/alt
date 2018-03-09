<?php

class Message_index extends ALT\Page {
    public function get($page) {
        $tpl = $this->template();
        if ($page) {
            // echo $page;
            $tpl->assign("content", App::Get($page));
        }
    }

    public function message($from = 0, $size = 25) {
        $start = $from++;
        $end = $from + $size;

        $this->write($size);
        $this->write($from);

        $count = App::User()->_Size("Message");
        $t = My::T(App::User()->Message(null, ["created_time", "desc"]));
        $t->add("", function() {
                return '<input type="checkbox" class="iCheck" />';
            }
            );
        $t->add("Creator", "CreatedBy()");
        $t->add("Title", "title");
        $t->add("Content", "content");
        $t->add("Time", function($o) {
                return $o->time() . " ago";
            }
            );
        $t->addClass("table-striped");

        $table = (<<<EOT
<div class="table-responsive mailbox-messages">
$t
</div>
EOT
            );

        $box = $this->createBox();
        $box->header("Message");
        $box->header()->tools()->append(<<<EOT
<div class="has-feedback">
  <input type="text" class="form-control input-sm" placeholder="Search">
  <span class="glyphicon glyphicon-search form-control-feedback"></span>
</div>
EOT
            );

        $box->body()->append(<<<EOT
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      {$start}-{$end}/{$count}
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
EOT
            );

        $box->body()->append($table);
        $this->write($box);
    }

    public function alert() {
        $this->write('test');
    }

    public function task() {
        $this->write('test');
    }
}

?>