<?php echo $header; ?>
<!-- BREADCRUMB -->
<div class="full-breadcrumb">
  <div class="title-breadcrumb font-sn">
      <?php $count = count($breadcrumbs);?>
      <?php $i=0; ?>
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <?php if( $i == $count-1){ ?>
             <?php echo '<b>'.$breadcrumb['text'].'</b>'; ?>
          <?php }else{ ?>
            <a href="<?php echo $breadcrumb['href']; ?>"></a>
          <?php } ?>      

        <?php $i++; ?> 
      <?php } ?>
  </div>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
</div>
<div class="container contacts">

<div class="main">
   
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
	
	   <?php if ($image) { ?>
		<div id="map-canvas"><img src="<?php echo $image; ?>" alt="<?php echo $store; ?>" title="<?php echo $store; ?>" /></div>
		<?php } ?>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBf-EZpcLV72omKDDxOlhG6-i8Ga8NenRo"></script>
        <?php if ($geocode) { ?>
          <script>
            function initialize() {
              var myLatlng = new google.maps.LatLng(<?php echo $geocode;?>);
              var mapOptions = {
                zoom: 16,
                zoomControl: false,
                scaleControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true,
                center: myLatlng
              }
              var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
              var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Furnicom!'
              });
            } 
            google.maps.event.addDomListener(window, 'load', initialize);  
          </script>
          <div id="map-canvas"></div>
        <?php } ?>
			
      <div class="info-contact row">
        <div class="col-sm-4 col-xs-12 info-store">
			<div class="name-store"><h3><?php echo $store; ?></h3></div>
			<?php if ($comment) { ?>
			  <div class="comment">
			  <?php echo $comment; ?></div>
			<?php } ?>
			<address>
			<div class="address clearfix form-group"><div class="pull-left"><i class="fa fa-home"></i></div><div class="text"><?php echo $address; ?></div></div>
			<div class="form-group"><div class="pull-left"><i class="fa fa-phone"></i></div><div class="text"><?php echo $telephone; ?></div></div>
			<?php if ($fax) { ?>
			<div class="form-group"><div class="pull-left"><i class="fa fa-fax"></i></div><div class="text"><?php echo $fax; ?></div></div>
			<?php } ?>
			<?php if ($open) { ?>
			<div class="form-group"><div class="pull-left"><i class="fa fa-clock-o"></i></div><div class="text"><?php echo $text_open; ?> <?php echo $open; ?></div>  </div>
			<?php } ?>
			<?php if ($geocode) { ?>
              <a href="https://maps.google.com/maps?q=<?php echo urlencode($geocode); ?>&hl=<?php echo $geocode_hl; ?>&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
              <?php } ?>
			</address>
			 
        </div>
        <div class="col-lg-8 col-sm-8 col-xs-12 contact-form">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
              <legend><?php echo $text_contact; ?></h2></legend>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label>
                <div class="col-sm-10">
                  <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
                  <?php if ($error_enquiry) { ?>
                  <div class="text-danger"><?php echo $error_enquiry; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php echo $captcha; ?>
            </fieldset>
            <div class="buttons">
              <div class="pull-right">
                <button class="btn btn-info" type="submit"><span><?php echo $button_submit; ?></span></button>
              </div>
            </div>
          </form>
        </div>
     
      </div>
</div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
