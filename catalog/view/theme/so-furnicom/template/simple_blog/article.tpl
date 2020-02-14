<?php 
$devices = array('lg' => ' Desktops','md' => ' Desktops','sm' => ' Tablets','xs' => ' Phones',);
$soconfig_pages= array('catalog_column_lg'=>'3','catalog_column_md'=>'3','catalog_column_sm'=>'2',);
?>

<?php echo $header; ?>
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
    <div class="container">
        
        <div class="row">
            <?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
                <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
                <?php $class = 'col-md-9 col-sm-8'; ?>
            <?php } else { ?>
                <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            
            <div id="content" class="<?php echo $class; ?>">
                <?php echo $content_top; ?>
                <div class="blog-header">
					<h2><?php echo $heading_title; ?></h2>
					<?php echo (isset($description) && !empty($description)) ? $description: ''; ?>
				</div>
                <!-- Filters -->
				<div class="product-filter product-filter-top filters-panel" style="display:none; float: left; width: 100%;">
					<div class="row">
						<div class="col-md-2 hidden-sm hidden-xs">
							<div class="view-mode">
								<div class="list-view">
									<button class="btn btn-default grid active" data-view="grid" ><i class="fa fa-th-large"></i></button>
									<button class="btn btn-default list " data-view="list"><i class="fa fa-th-list"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-10"><div class="pull-right"><?php echo $pagination; ?></div></div>
					</div>
				</div>
				<!-- //end Filters -->
                <div class="blog-listitem">
                    <?php if($articles) { ?>
                        <?php foreach($articles as  $id_article => $article) { ?>                            
                        <div class="blog-item">
							<?php if($article['image']) { ?>
							<div class="itemBlogImg left-block">
								<div class="article-image banners ">
									<div>
										<a  class="popup-gallery" href="<?php echo $article['image']; ?>"><img  src="<?php echo $article['image']; ?>" alt="<?php echo $article['article_title']; ?>" /></a>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="itemBlogContent right-block">
								<div class="blog-bg">
									<div class="blog-data">
								  		<!-- NAME TITLE-->
										<div class="article-title">
											<h4><a href="<?php echo $article['href']; ?>"><?php echo ucwords($article['article_title']); ?></a></h4>
										</div>
										<!-- COMMENT -->
								  		<div class="blog-meta">
								  			<span class="author"><i class="fa fa-user"></i><span>Post by </span><?php echo $article['author_name']; ?></span>
											<?php if($article['allow_comment']) { ?>
												<span class="comment_count"><i class="fa fa-comment-o"></i><a href="<?php echo $article['comment_href']; ?>#comment-section"><?php echo $article['total_comment']; ?></a></span>
											<?php } ?>												
											<span class="article-date hidden" >
											    <?php $datetotime = strtotime($article['date_added']); ?>
											    <i class="fa fa-calendar"></i><?php echo  $datetotime;?>
											</span>
										</div>
										
									 	<!-- DESCRIP -->
										<p class="article-description" style="visibility:visible!important;">
											 <?php echo $article['description'];?>
										</p>
										<a class="btn btn-default hidden" href="<?php echo $article['href']; ?>"><b><?php echo $button_continue_reading; ?></b><i class="fa fa-angle-right"></i></a>
									</div>
							 	</div>
							</div>                                
                        </div>
                        <?php } ?>
                        
                       
                           
                    <?php } else { ?>
                        <h3 class="text-center"><?php echo $text_no_found; ?></h3>
                    <?php } ?>
                </div> 
				
                <!-- Filters -->
				<div class="product-filter product-filter-bottom filters-panel clearfix" >
					<div class="row">
						<div class="col-md-2 hidden-sm hidden-xs">
							<div class="view-mode">
								<div class="list-view">
									<button class="btn btn-default grid active" data-view="grid" ><i class="fa fa-th-large"></i></button>
									<button class="btn btn-default list " data-view="list"><i class="fa fa-th-list"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-10"><div class="pull-right"><?php echo $pagination; ?></div></div>
					</div>
				</div>
				<!-- //end Filters -->
                <?php echo $content_bottom; ?>
            </div>            
            
            <?php echo $column_right; ?>
        </div>        
    </div>    
<?php echo $footer; ?>

<script type="text/javascript"><!--
function display(view) {
	$('.blog-listitem').removeClass('list grid').addClass(view);
	$('.list-view .btn').removeClass('active');
	if(view == 'list') {
		$('.blog-listitem .blog-item').addClass('col-md-12');
		$('.blog-listitem .blog-item').removeClass('col-md-6');
		$('.blog-listitem .blog-item .left-block').addClass('col-md-4');
		$('.blog-listitem .blog-item .right-block').addClass('col-md-8');
		$('.blog-listitem .blog-item .article-description').removeClass('hidden')
		$('.list-view .' + view).addClass('active');
		$.cookie('simple_blog_category', 'list'); 
	}else{
		$('.blog-listitem .blog-item').addClass('col-md-6');
		$('.blog-listitem .blog-item').removeClass('col-md-12');
		$('.blog-listitem .blog-item .left-block').removeClass('col-md-4');
		$('.blog-listitem .blog-item .right-block').removeClass('col-md-8');
		$('.blog-listitem .blog-item .article-description').addClass('hidden');
		$('.list-view .' + view).addClass('active');
		$.cookie('simple_blog_category', 'grid');
	}
}


$(document).ready(function () {
	// Check if Cookie exists
	if($.cookie('simple_blog_category')){
		view = $.cookie('simple_blog_category');
	}else{
		view = 'grid';
	}
	if(view) display(view);
	
	// Click Button
	$('.list-view .btn').each(function() {
		var ua = navigator.userAgent,
		event = (ua.match(/iPad/i)) ? 'touchstart' : 'click';
		$(this).bind(event, function() {
			$(this).addClass(function() {
				if($(this).hasClass('active')) return ''; 
				return 'active';
			});
			$(this).siblings('.btn').removeClass('active');
			$catalog_mode = $(this).data('view');
			display($catalog_mode);
		});
		
	});
});

//--></script> 