<div class="list-group">
  <?php if( get_field('download_pdf_button', 'options') ) { ?>      <?php if(function_exists('mpdf_pdfbutton')) ?><?php { mpdf_pdfbutton(' Download as PDF', ' Download as PDF'); } ?><?php } ?>
  <?php if( get_field('call_back_button', 'options') ) { ?>         <a class="list-group-item" href="#request-call-back" data-toggle="modal"><span class="glyphicons headset"></span> Request Call Back</a><?php } ?>
  <?php if( get_field('request_literature_button', 'options') ) { ?><a class="list-group-item" href="#request-literature" data-toggle="modal"><span class="glyphicons book"></span> Request Literature</a><?php } ?>
  <?php if( get_field('share_button', 'options') ) { ?>             <a class="list-group-item" href="#share" data-toggle="modal"><span class="glyphicons share"></span> Share this Page</a><?php } ?>
</div>