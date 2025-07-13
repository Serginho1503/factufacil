<?php
	$this->load->view("theme/head");
	$this->load->view("theme/header");
	$this->load->view("theme/menu");
	?>
	<div id=app>
      		<?php $this->load->view($content);?>
    </div>
    <script    src="<?php echo base_url() ?>public/frontend/dist/build.js"></script>
<?php	
	$this->load->view("theme/footer");




?>