<div class="list-group">
  <?php if( get_field('download_pdf_button', 'options') ) { ?>
  	<?php if(function_exists('mpdf_pdfbutton')) ?><?php { mpdf_pdfbutton(' Download as PDF', ' Download as PDF'); } ?>
  <?php } ?>
  <?php if( get_field('call_back_button', 'options') ) { ?>
  	<a class="list-group-item" href="#request-call-back" data-toggle="modal"><i class="fa fa-phone"></i> Request Call Back</a>
  <?php } ?>
  <?php if( get_field('request_literature_button', 'options') ) { ?>
  	<a class="list-group-item" href="#request-literature" data-toggle="modal"><i class="fa fa-book"></i> Request Literature</a>
  <?php } ?>
  <?php if( get_field('share_button', 'options') ) { ?>
  	<a class="list-group-item" href="#share" data-toggle="modal"><i class="fa fa-share"></i> Share this Page</a>
  <?php } ?>
</div>