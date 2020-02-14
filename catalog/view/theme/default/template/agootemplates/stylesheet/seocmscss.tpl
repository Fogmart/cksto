/* <?php if (SC_VERSION > 15) { ?> */
img.close {  display: none; }
/* <?php } else { ?> */
button.close { 	display: none; }
/* <?php } ?> */


.blog-small-record img {
    vertical-align: top;
}
.blog-small-record {
    padding-left: 0px;
    height: 20px;
}
.blog-small-record li {
    float: left;
    padding: 0 15px 0 0;
}

.blog-small-record ul li {
    left: 0;
    list-style: none outside none;
}
.blog-small-record ul {
    padding: 0;
    margin: 0;
}

.blog-small-record li:first-child {
	margin-left: 0;
	left: 0;
	padding-left: 0;
}

<?php if (SC_VERSION < 20) { ?>


.blog-small-record li {
    float: left;
    padding: 0 18px;
}

.blog-small-record li:first-child {
	padding-left: 18px;
}


ins.ascp_list_grid {
    height: 16px;
    width: 16px;
    line-height: 16px;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    font-size: 100%;
    font-weight: normal;
    text-decoration: none;
    float: left;
}
ins.ascp_grid {
    background: url("<?php echo getCSSimage('ascp_grid.png', $config_template, $options); ?>") no-repeat 0px 0px transparent;
}
ins.ascp_grid:hover,  ins.ascp_grid_active {
    background: url("<?php echo getCSSimage('ascp_grid.png', $config_template, $options); ?>") no-repeat 0px -16px transparent;
}
ins.ascp_list {
    background: url("<?php echo getCSSimage('ascp_list.png', $config_template, $options); ?>") no-repeat 0px 0px transparent;
}
ins.ascp_list:hover {
    background: url("<?php echo getCSSimage('ascp_list.png', $config_template, $options); ?>") no-repeat 0px -16px transparent;
}
ins.ascp_list_active {
    background: url("<?php echo getCSSimage('ascp_list.png', $config_template, $options); ?>") no-repeat 0px -16px transparent;
}
<?php } ?>


<?php if (SC_VERSION > 15) { ?>

a #ascp_list:after {
    content: '\f00b\0020';
    font-family: FontAwesome;


}

a #ascp_grid:after {
    content: '\f00a\0020';
    font-family: FontAwesome;

}

#ascp_list, #ascp_grid {
	color: #aaa;

	font-weight: normal;
	font-size: 1.5em;
}


a:hover #ascp_list, a:hover #ascp_grid, #ascp_list.ascp_list_active, #ascp_grid.ascp_grid_active  {
    color: #555;
}


<?php } ?>


ul.ascp_list_info li {
    width: auto;
    max-width: 100%;
    <?php if (SC_VERSION < 20) { ?>
    padding-left: 20px;
    <?php } else { ?>
    /* padding-left: 5px; */
    <?php } ?>
    margin-right: 8px;
}

ul.ascp_list_info li:first-child {
	margin-left: 0;
	left: 0;
<?php if (SC_VERSION > 15) { ?>
	padding-left: 0;
<?php } ?>
}

.blog-data-record {
    <?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('time_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0 transparent;
    <?php } ?>
    color: #aaa;
}

<?php if (SC_VERSION > 15) { ?>
.blog-data-record:before {
    content: '\f017\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>




.blog-comments-record {
    <?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('comments_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0px transparent;
    <?php } ?>
    color: #aaa;
}
<?php if (SC_VERSION > 15) { ?>
.blog-comments-record:before {
    content: '\f0e5\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>


.blog-comments-karma {
    <?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('comments_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0px transparent;
    <?php } ?>
    color: #aaa;
}
<?php if (SC_VERSION > 15) { ?>
.blog-comments-karma:before {
    content: '\f087\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>



.blog-viewed-record {
    <?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('viewed_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0px transparent;
    <?php } ?>
    color: #aaa;

}
<?php if (SC_VERSION > 15) { ?>
.blog-viewed-record:before {
    content: '\f06e\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>



.blog-category-record {
    <?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('category_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0px transparent;
    <?php } ?>
    color: #aaa;

}
<?php if (SC_VERSION > 15) { ?>
.blog-category-record:before {
    content: '\f114\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>


.blog-author-record {
	<?php if (SC_VERSION < 20) { ?>
    background: url("<?php echo getCSSimage('author_ascp.png', $config_template, $options); ?>") no-repeat scroll 0 0px transparent;
    <?php } ?>
    color: #aaa;

}

<?php if (SC_VERSION > 15) { ?>
.blog-author-record:before {
    content: '\f007\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>



.blog-list-category {
    color: #aaa;

}
<?php if (SC_VERSION > 15) { ?>
.blog-list-category:before {
    content: '\f114\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>

.blog-list-record {
    color: #aaa;

}
<?php if (SC_VERSION > 15) { ?>
.blog-list-record:before {
    content: '\f016\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>

.blog-list-manufacturer {
    color: #aaa;

}
<?php if (SC_VERSION > 15) { ?>
.blog-list-manufacturer:before {
    content: '\f11d\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}
<?php } ?>


h9.blog-icon {
    <?php if (SC_VERSION < 20) { ?>
    background-image: url("<?php echo getCSSimage('ib.png', $config_template, $options); ?>");
    <?php } ?>
    height: 16px;
    width: 16px;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    font-size: 90%;
    font-weight: normal;
}

.record_content {
   /* <?php if (isset($settings_general['css']['record-content']) && $settings_general['css']['record-content']!='') { ?> */
    background-color: /* <?php if (1==1) echo html_entity_decode(' *&#47; ').$settings_general['css']['record-content'].html_entity_decode(' &#47;* '); else { ?> */ #000 /* <?php } ?> */ !important;
   /* <?php } ?> */
}

.blog-content {
   /* <?php if (isset($settings_general['css']['blog-content']) && $settings_general['css']['blog-content']!='') { ?> */
    background-color: /* <?php if (1==1) echo html_entity_decode(' *&#47; ').$settings_general['css']['blog-content'].html_entity_decode(' &#47;* '); else { ?> */ #000 /* <?php } ?> */ !important;
   /* <?php } ?> */
}
.ascp-list-title {
    	/* <?php if (isset($settings_general['css']['ascp-list-title-color']) && $settings_general['css']['ascp-list-title-color']!='') { ?> */
    	color: <?php echo $settings_general['css']['ascp-list-title-color']; ?> !important;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-size']) && $settings_general['css']['ascp-list-title-size']!='') { ?> */
        font-size: <?php echo $settings_general['css']['ascp-list-title-size']; ?> !important;
        /* <?php  } else { ?> */
        font-size: 120%;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-line']) && $settings_general['css']['ascp-list-title-line']!='') { ?> */
        line-height: <?php echo $settings_general['css']['ascp-list-title-line']; ?> !important;
        /* <?php  } else { ?> */
        line-height: 140%;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-decoration']) && $settings_general['css']['ascp-list-title-decoration']!='') { ?> */
        text-decoration: <?php echo $settings_general['css']['ascp-list-title-decoration']; ?> !important;
        /* <?php }  else {  ?> */
        text-decoration: none;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-weight']) && $settings_general['css']['ascp-list-title-weight']!='') { ?> */
        font-weight: <?php echo $settings_general['css']['ascp-list-title-weight'];  ?> !important;
        /* <?php  }  else {  ?> */
        font-weight: normal;
        /* <?php } ?> */
}
.ascp-list-title-widget {
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-color']) && $settings_general['css']['ascp-list-title-widget-color']!='') { ?> */
    color: <?php echo $settings_general['css']['ascp-list-title-widget-color']; ?> !important;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-size']) && $settings_general['css']['ascp-list-title-widget-size']!='') { ?> */
    font-size: <?php echo $settings_general['css']['ascp-list-title-widget-size'];  ?> !important;
    /* <?php  }  else {  ?> */
    font-size: 120%;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-line']) && $settings_general['css']['ascp-list-title-widget-line']!='') { ?>*/
    line-height: <?php echo $settings_general['css']['ascp-list-title-widget-line']; ?> !important;
    /* <?php }  else { ?> */
    line-height: 140%;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-decoration']) && $settings_general['css']['ascp-list-title-widget-decoration']!='') { ?> */
    text-decoration: <?php echo $settings_general['css']['ascp-list-title-widget-decoration']; ?> !important;
    /* <?php } else {  ?> */
    text-decoration: none;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-weight']) && $settings_general['css']['ascp-list-title-widget-weight']!='') {  ?> */
    font-weight: <?php echo $settings_general['css']['ascp-list-title-widget-weight']; ?> !important;
    /* <?php }  else { ?> */
    font-weight: normal;
    /* <?php } ?> */
}
/* <?php if (isset($settings_general['css']['css']) && $settings_general['css']['css']!='') {
    echo html_entity_decode('*&#47; ').html_entity_decode($settings_general['css']['css'], ENT_QUOTES, 'UTF-8').html_entity_decode(' &#47;*');
} ?> */