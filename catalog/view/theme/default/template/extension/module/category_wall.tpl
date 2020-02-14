<?php
  $path = "common/home";
  $url = $_SERVER['REQUEST_URI'];
  if ($url == "/" or strripos($url, $path)) {
  }else{
   ?>
 	<div class="so-spotlight1 container"></div>
	<div class="so-spotlight2 container">
<?php  } ?>




	
		<div class="title-home"> 
			<h2><?php echo $heading_title; ?></h2>
		</div>

<div class="owl2-stage-outer">
	<div class="owl2-stage" style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 110%;">
   		<div class="product-item-container">
			<?php foreach ($categories as $category) { ?>
      
				<div class="item ">
					<div class="product-layout" >
						<div class="product-item-container"  style="width: 260.5px; margin-right: 31px; opacity: 1; float:left; margin-top:30px;">
							<div class="left-block" style="text-align: center;}">
								<div class="product-image-container">
									<a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a>
								</div>
								<div class="right-block">
									<div class="caption" style="width: 100%; height: 45px;margin: 8px auto;">
										<span style="text-decoration: none">
											<a href="<?php echo $category['href']; ?>"><?php echo $category['nameshort']; ?></a>
										</span>
									</div> 
            							</div>
        						</div>
						</div>
					</div>
				</div> 

			<?php } ?>
</div> </div> 
<?php
  $path = "common/home";
  $url = $_SERVER['REQUEST_URI'];
  if ($url == "/" or strripos($url, $path)) {
  }else{
   ?>
	</div>
<?php  } ?>

