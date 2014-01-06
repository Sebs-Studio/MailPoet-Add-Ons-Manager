<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly ?>

<?php require_once($this->plugin_path.'include/add-ons-list.php'); ?>

<div class="module-container">
	<h2><?php _e('MailPoet Add-ons'); ?></h2>

<?php
foreach($available_add_ons as $plugin => $product){
	if(empty($product['official']) || $product['official'] == 'yes'){

	$status = ''; // Status class.

	/**
	 * Queries if the plugin is installed, 
	 * active and meets the requirements 
	 * it requires if any.
	 */
	if(file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ $status .= ' installed'; }else{ $status .= ' not-installed'; }
	if(is_plugin_active($product['plugin_url'])){ $status .= ' active'; }else{ $status .= ' inactive'; }
	if(empty($product['requires'])){ $status .= ' ready'; }
	else if(!empty($product['requires']) && file_exists($this->wp_plugin_path.''.plugin_basename($product['requires']))){
		$status .= ' ready';
		if(is_plugin_active($product['requires'])){ $status .= ' ready'; }
		else{ $status .= ' not-ready'; }
	}
	else if(!empty($product['requires']) && !file_exists($this->wp_plugin_path.''.plugin_basename($product['requires']))){ $status .= ' not-ready'; }
?>

<div class="mailpoet-module<?php echo $status; ?>" id="product">

	<h3><?php echo $product['name']; ?></h3>

	<?php if(!empty($product['thumbnail'])){ ?><div class="mailpoet-module-image"><img src="<?php echo $this->assets_url.'images/plugins/'.$product['thumbnail']; ?>" width="100%" title="<?php echo $product['name']; ?>" alt=""></div><?php } ?>

	<div class="mailpoet-module-content">

		<div class="mailpoet-module-description">
		<p><?php echo $product['description']; ?><?php /*if(!empty($product['version'])){ echo '&nbsp;-&nbsp;v&nbsp;'.$product['version']; }*/ ?></p> 
		<p><?php if(!empty($product['review'])){ echo '<strong>'.sprintf(__('MailPoet says:&nbsp;<em>%s</em>'), $product['review']).'</strong>'; } ?></p> 
		<?php if( file_exists($this->wp_plugin_path.''.plugin_basename('wysija-newsletters-premium/index.php')) && !empty($product['premium_offer']) ){ ?><p><strong><?php echo $product['premium_offer']; ?></strong></p><?php } ?> 
		</div>

		<div class="mailpoet-module-actions">
			<?php if(!empty($product['author_url'])){ ?><a href="<?php echo esc_url($product['author_url']); ?>" target="_blank" class="button-primary website"><?php _e('Website'); ?></a>&nbsp;<?php } ?> 
			<?php if($product['free'] == 'yes' && !empty($product['download_url'])){ if(!file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ ?><a href="<?php echo $product['download_url']; ?>" target="_blank" class="button-primary download"><?php _e('Download Plugin'); ?></a>&nbsp;<?php } } ?> 
			<?php if($product['service'] == 'no'){ ?> 
			<?php if($product['on_wordpress.org'] == 'yes'){ if(!file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ ?><a href="<?php echo admin_url('plugin-install.php?tab=search&type=term&s='.strtolower(str_replace(' ', '+', $product['search']))); ?>" class="button-primary install"><?php _e('Install from WordPress.org'); ?></a>&nbsp;<?php } } ?> 
			<?php if(file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ if(!is_plugin_active($product['plugin_url'])){ ?><a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->page_slug.'&amp;action=activate&amp;module='.$product['plugin_url'])); ?>" class="button-primary activate"><?php _e('Activate'); ?></a>&nbsp;<?php }else{ ?> 
			<!--a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->page_slug.'&amp;action=deactivate&amp;module='.str_replace(' ', '-', strtolower($product['plugin_url'])))); ?>" class="mailpoet-deactivate-button button-secondary"><?php _e('Deactivate'); ?></a>&nbsp;-->
			<?php if(!empty($product['config_url'])){ ?><a href="<?php echo $product['config_url']; ?>" class="mailpoet-configure-button button-secondary"><?php _e('Configure'); ?></a><?php } } } ?> 
			<?php } ?> 
		</div>
	</div>

</div>

<?php
	} // end if local is yes.
}
?>
</div><!-- .module-container -->

<div class="submit-idea">
	<p><?php echo sprintf(__('Don\'t see the add-on you\'re looking for? <a href="%s" target="_blank">Submit it</a> in our contact form.'), 'http://www.mailpoet.com/contact/'); ?></p>
</div>

<div class="module-container">
	<h2><?php _e('Works with MailPoet'); ?></h2>
	<p><?php _e('This list of plugins and services that might be useful to you. We don\'t offer support for them, and we\'re not affiliated with them.'); ?></p>

<?php
foreach($available_add_ons as $plugin => $product){

	if($product['official'] == 'no'){

	$status = ''; // Status class.

	/**
	 * Queries if the plugin is installed, 
	 * active and meets the requirements 
	 * it requires if any.
	 */
	if(file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ $status .= ' installed'; }else{ $status .= ' not-installed'; }
	if(is_plugin_active($product['plugin_url'])){ $status .= ' active'; }else{ $status .= ' inactive'; }
	if(empty($product['requires'])){ $status .= ' ready'; }
	else if(!empty($product['requires']) && file_exists($this->wp_plugin_path.''.plugin_basename($product['requires']))){
		$status .= ' ready';
		if(is_plugin_active($product['requires'])){ $status .= ' ready'; }
		else{ $status .= ' not-ready'; }
	}
	else if(!empty($product['requires']) && !file_exists($this->wp_plugin_path.''.plugin_basename($product['requires']))){ $status .= ' not-ready'; }
?>

<div class="mailpoet-module<?php echo $status; ?>" id="product">

	<h3><?php echo $product['name']; ?></h3>

	<?php if(!empty($product['thumbnail'])){ ?><div class="mailpoet-module-image"><img src="<?php echo $this->assets_url.'images/plugins/'.$product['thumbnail']; ?>" width="100%" title="<?php echo $product['name']; ?>" alt=""></div><?php } ?>

	<div class="mailpoet-module-content">

		<div class="mailpoet-module-description">
		<p><?php echo $product['description']; ?><?php /*if(!empty($product['version'])){ echo '&nbsp;-&nbsp;v&nbsp;'.$product['version']; }*/ ?></p> 
		<p><?php if(!empty($product['review'])){ echo '<strong>'.sprintf(__('MailPoet says:&nbsp;<em>%s</em>'), $product['review']).'</strong>'; } ?></p> 
		<?php if( file_exists($this->wp_plugin_path.''.plugin_basename('wysija-newsletters-premium/index.php')) && !empty($product['premium_offer']) ){ ?><p><strong><?php echo $product['premium_offer']; ?></strong></p><?php } ?> 
		</div>

		<div class="mailpoet-module-actions">
			<?php if(!empty($product['author_url'])){ ?><a href="<?php echo esc_url($product['author_url']); ?>" target="_blank" rel="external" class="button-primary website"><?php _e('Website'); ?></a>&nbsp;<?php } ?> 
			<?php
			if($product['free'] == 'no' && !empty($product['purchase_url'])){
				if(!empty($product['plugin_url']) && !file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ ?><a href="<?php echo $product['purchase_url']; ?>" target="_blank" rel="external" class="button-primary purchase"><?php _e('Purchase'); ?></a>&nbsp;
			<?php
				} // end if plugin is installed, don't show purchase button.
			} // end if product is not free.
			?> 

			<?php
			if($product['service'] == 'no'){
				if($product['on_wordpress.org'] == 'yes'){ 
					if(!file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){ ?><a href="<?php echo admin_url('plugin-install.php?tab=search&type=term&s='.strtolower(str_replace(' ', '+', $product['search']))); ?>" class="button-primary install"><?php _e('Install from WordPress.org'); ?></a>&nbsp;
					<?php } // end if file_exists.
				} // end if $product['on_wordpress.org'];

				if(!empty($product['plugin_url']) && file_exists($this->wp_plugin_path.''.plugin_basename($product['plugin_url']))){
					if(!is_plugin_active($product['plugin_url'])){ ?><a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->page_slug.'&amp;action=activate&amp;module='.$product['plugin_url'])); ?>" class="button-primary activate"><?php _e('Activate'); ?></a>&nbsp;<?php }else{ ?> 
					<!--a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->page_slug.'&amp;action=deactivate&amp;module='.str_replace(' ', '-', strtolower($product['plugin_url'])))); ?>" class="mailpoet-deactivate-button button-secondary"><?php _e('Deactivate'); ?></a>&nbsp;-->
					<?php if(!empty($product['config_url'])){ ?><a href="<?php echo $product['config_url']; ?>" class="mailpoet-configure-button button-secondary"><?php _e('Configure'); ?></a><?php } // end if ?>
					<?php
					}
				}
			} // end if plugin is installed. ?> 
		</div>
	</div>

</div>

<?php
	} // end if local is yes.
}
?>
</div><!-- .module-container -->