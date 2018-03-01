﻿<?php
 require_once("config.php");
 require_once("beginning.php");
 $noposts = false;
 if ((isset($_GET['report'])) && $reports == false)
 {
  echo "<div class='alert alert-danger mx-auto'>" . $LANG['report_err_disabled'] . "</div>";
 }
 foreach ($server->query("SELECT COUNT(*) AS total FROM " . $credentials['ptable']) as $rows)
 {
 $amount = $rows['total'];
 }
  if ( $amount > 0 )
  {
   if (isset($_GET['p']))
   {
    $p = $_GET['p'];
   }
   else
   {
    $p = 1;
   }
   $shown = 0; $extra = '';
   foreach($server->query("SELECT * FROM " . $credentials["ptable"] . " ORDER BY date DESC LIMIT " . ($p - 1) . $amountpage) as $rows) {
   $shown = $shown + 1;
   if ($shown == $amountpage)
   {
    $extra = "id='last'";
   }
    echo "<div class='posts' " . $extra . ">
     " . ($reports ? "<form method=get action=''><input name='report' type=hidden value='$rows[pid]'>" : "") . "<div class='card text-white bg-dark mb-2 mx-auto' >";
     if ($reports)
	 {
	  if ($rows['rep'] == 0)
	  {
	   $status = 'badge-success';
	  }
	  else
	  {
	   if ($rows['rep'] <= ($maxrep / 2))
	   {
	    $status = 'badge-warning';
	   }
	   else
	   {
	    $status = 'badge-danger';
	   }
	  }
	 }
     echo "<div class='card-header'>$rows[date]" . ($reports ? "<button class='btn btn-danger float-right btn-sm'>" . $LANG['report_button'] . "</button><span class='badge $status float-right'>" . $rows['rep'] . "/" . $maxrep . "</span>" : "") . "</div>
     " . ($reports ? "</form>" : "") . "
     <div class='card-body'>$rows[cont]";
	 if ( ! empty($rows['img']))
	 {
      echo "
	  <div class='imgcontainer mx-auto'>
	   <img class='img-thumbnail' src='" . $rows['img'] . "' alt='" . $LANG['alt_broken_image'] . "'>
	  </div>";
	 }
	 echo "
	 </div>
     <div class='card-footer'>" . $LANG['posted_by'];

    if ((isset($rows['nick'])) && (empty($rows['nick'])))
    {
     echo "<i>" . $LANG['no_nick'] . "</i>";
    }
    else
    {
   	echo "$rows[nick]";
    }
    echo "<a href='comment.php?pid=$rows[pid]' class='float-right'><span class='octicon octicon-comment-discussion'></span> " . $LANG['comment_button_create'] . "</a>
      </div>
	   ";
	   foreach($server->query("SELECT * FROM " . $credentials["ctable"] . " WHERE pid = " . $rows['pid']) as $rowscom)
	   {
	   echo "
     <div class='card bg-gradient-dark text-white pb-4' id='comments'>
	    <div class='card-header' id='cheader'>" . (empty($rowscom['nick']) ? "<i>" . $LANG['no_nick'] . "</i>":$rowscom['nick']) . " " . $LANG['comment_after_nick'] . "</div>
	    <div class='card-body'>" . $rowscom['cont'] . "</div>";
      if ( ! empty($rowscom['img']))
      {
       echo "
        <div class='imgcontainer mx-auto'>
         <img class='img-thumbnail' src='" . $rowscom['img'] . "' alt='" . $LANG['alt_broken_image'] . "' >
        </div>
   	   </div>";
      }
	   }
	 echo "
	  </div>
   </div>";
   }
  }
  else
  {
   $noposts = true;
   echo "
   <div class='alert alert-primary mx-auto'>" . $LANG['no_data_a'] . "<a href='/create.php'>" . $LANG['no_data_b'] . "</a></div>
   ";
   }
  if ((isset($_GET['report'])) && $reports)
  {
   foreach($server->query("SELECT * FROM `" . $credentials['ptable'] . "` WHERE `pid` = " . $server->quote($_GET['report'])) as $rows)
   {
	if ($rows['rep'] > ($maxrep - 1))
	{
	 $server->query("DELETE FROM `" . $credentials['ptable'] . "` WHERE `pid` = " . $server->quote($_GET['report']));
	}
	else
	{
     $server->query("UPDATE `" . $credentials['ptable'] . "` SET `rep`=rep+1 WHERE `pid` = " . $server->quote($_GET['report']));
    }
   }
  }
 // Load the Infinite Scroll status indicator
 echo "
  <div class='page-load-status'>
   <div class='alert alert-primary mx-auto' id='load'> " . $LANG['is_loading'] . "</div>
   <div class='alert alert-light mx-auto' id='end'>" . $LANG['is_lastpage_a'] . "<a href='" . $root . "'>" . $LANG['is_lastpage_b'] . "</a></div>
  </div>";
 loadscripts();
 echo "
  <script type='text/javascript'>
  var amountpage = " . $amountpage . ";
  $(window).scroll(function (event) {
    if($(window).scrollTop() + $(window).height() >= $(document).height() - " . $offset . ")
    {
     $('#loading').css('display', 'block');
     $.get('fetchdata.php?&row=' + amountpage + '&', function(data)
     {
       content = data;
       if(content === '') { $(window).off('scroll'); $('#load').css('display', 'none'); $('#end').fadeIn(500); }
       $('#last').append(content);
     });
     amountpage = amountpage + 1;
    };
   });
  </script>
 ";
 if ( ! $noposts)
 {
  echo "
  <div class='container-fluid fixed-bottom' id='createpost' >
   <form action='" . $root . "/create.php'>
    <button type='submit' class='btn float-right'>
	   <span class='octicon octicon-plus' aria-hidden='true'></span>
    </button>
   </form>
  </div>
  ";
 }
 require_once('footer.php');
?>
