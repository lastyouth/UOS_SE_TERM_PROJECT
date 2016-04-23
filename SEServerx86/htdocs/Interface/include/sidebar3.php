  <div class="sidebar">  
    <aside class="span4">
      <?php include 'sidebar-content.php'; ?>
    </aside>
  </div><!--.sidebar-->  
  </div><!--.row-->  
</div><!--.container-->  


<script>
$(function() {
  $("#my-collapse-nav > li > a[data-target]").parent('li').hover(
      function() { 
          target = $(this).children('a[data-target]').data('target');
          $(target).collapse('show') 
      },
      function() { 
          target = $(this).children('a[data-target]').data('target'); 
          $(target).collapse('hide');
      }
  );
});
</script>


