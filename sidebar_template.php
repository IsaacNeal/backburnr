<script src="js/poll.js" type="text/javascript"></script>
<script type="text/javascript">
window.onload = function(){list_nums();}
</script>
<div class="sidebar1">
   <ul class="nav">
      <li><a href="http://www.youtube.com/playlist?list=PL0BBakFmJ-JneCF-aDQyO-Id-ltCSqJT1" target="_blank">Video Lessons</a></li>
      <li><a href="http://www.youtube.com/user/iPriceProductions" target="_blank">Subscribe</a></li>
      <li><a href="https://github.com/IsaacNeal/backburnr" target="_blank">Source Code</a></li>
      <li><a href="http://www.worldofwebcraft.com" target="_blank">Forums</a></li>
      <li><a href="#">WTF?</a></li>
    </ul>
    <div style="padding:6px; text-align:center;"><strong>Which group Pisses You Off More?</strong></div>
    <div style="padding:6px;">
    <form id="my_form" style="padding:6px;"><label style="cursor:pointer;"><input type="radio" id="radio1" name="radio" value="1" />Conservatives</label><span id="count1" style="float:right; padding-right:5px;"></span>
  <br />
  <br />
  <label style="cursor:pointer;"><input type="radio" id="radio2" name="radio" value="2" />Liberals</label><span id="count2" style="float:right; padding-right:5px;"></span>
  <br />
  <br />
  <label style="cursor:pointer;"><input type="radio" id="radio3" name="radio" value="3" />The Amish</label><span id="count3" style="float:right; padding-right:5px;"></span>
  <p class="submit" style="text-align:center;">
  <br />
  <button type="button" onclick="ajax_post();" onmouseout="list_nums();" value="Cast Vote">Cast Vote</button>
  </p>
  <p id="status"></p>
  </form>
  </div>
    &nbsp;<img src="images/sidebar_logo.png" alt="backburnr" style="width:94%;"/>
</div>