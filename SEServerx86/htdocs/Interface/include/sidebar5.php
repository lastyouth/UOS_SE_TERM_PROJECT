  <div class="sidebar">  
    <aside class="span4">
      <?php include 'sidebar-content.php'; ?>
    </aside>
  </div><!--.sidebar-->  
  </div><!--.row-->  
</div><!--.container-->  

<nav id="page-nav">
  <a href="blog-pages/index2.php"></a>
</nav>

<script>
  $(function(){

    var $container = $('#content');
// infinitescroll() is called on the element that surrounds 
// the items you will be loading more of
      $container.infinitescroll({
      navSelector  : '#page-nav',    // selector for the paged navigation 
      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      itemSelector : 'article',     // selector for all items you'll retrieve
      
      loading: {
          finishedMsg: '로드할 페이지가 더 이상 없습니다.',
          msgText : "<em>다음 페이지를 로딩합니다.</em>",
          img: 'http://i.imgur.com/6RMhx.gif'
       }
      }); 
  });
</script>



