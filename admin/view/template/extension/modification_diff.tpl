<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-file-o"></i> <?php echo $file_patch; ?></h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_help_diff; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div id="flex-container" style="width:100% !important; height:500px; position:relative; font-size:1.1em;">
          <div>
            <div id="acediff-left-editor"><?php echo htmlentities($code_original); ?></div>
          </div>
          <div id="acediff-gutter"></div>
          <div>
            <div id="acediff-right-editor"><?php echo htmlentities($code_cache); ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="view/javascript/ace/diff.min.js"></script>
<script type="text/javascript"><!--
  var aceDiffer = new AceDiff({
    mode: "ace/mode/php",
    theme: "ace/theme/chrome",
    left: { editable: false },
    right: { editable: false }
  });
//--></script> 
<?php echo $footer; ?>