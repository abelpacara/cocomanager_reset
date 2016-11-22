</div>
  <div id="footer_wrapper">
     <?php
     $prefix_logo = "default";
     
     ?>
     <div id="footer">
      <div id="logo_section" class="footer_text">
         <div id="logo_footer" class="enqueue_by_right">
            <a href="<?php echo base_url(); ?>" id="icon_link_footer">
                  <img src="<?php echo site_url($uri_images_companies."/".$prefix_logo."_footer-logo.jpg");?>">
            </a>
         </div>
         <div id="label_logo" class="enqueue_by_right">
            Company Control Manager
         </div>
      </div>
      
      <div id="credits_section" class="footer_text"> 
         Cualquier consulta dirijase 
         <a id="link_us" href="<?php echo site_url("forums")?>">Aqui</a> |
         Desarrollado por
         <a id="link_us" href="<?php echo site_url()?>">Bolivia Web Design</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
