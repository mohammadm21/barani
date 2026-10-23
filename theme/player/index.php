<!DOCTYPE html>
<html>
    <head>
        <title><?php echo(site_title); ?> | موزیک پلیر </title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>
        <link href="<?php echo(theme::url('css/player.css')); ?>" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="<?php echo(url('theme/js/jquery.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo(theme::url('js/player.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo(theme::url('js/playlist.js')); ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                new jPlayerPlaylist({
                    jPlayer: "#player",
                    cssSelectorAncestor: "#container"
                }, [
<?php
foreach($sql->get('player') as $row){
    ?>
                        {
                            title: "<?php echo($row['title']); ?>",
                           // mp3: "<?php echo(url($row['src'])); ?>"
                            mp3: "<?php echo($row['src']); ?>"
                        },
    <?php
}
mysqli_free_result($query);
?>
                ], {
                    swfPath: "<?php echo(theme::url('player.swf')); ?>",
                    supplied: "mp3",
                    wmode: "window",
                    preload: "none",
                    useStateClassSkin: true,
                    autoBlur: false,
                    autoPlay: false,
                    smoothPlayBar: true,
                    keyEnabled: true
                });
            });
        </script>
          <script>
            $(document).ready(function(){
 			$('#sstop').click(function(){
     	  
    		});
  			// set time out 2 sec
   		  setTimeout(function(){
        $('#sstop').trigger('click');
    }, 500);
	});
      </script>
    </head>
    <body>
        <div id="player" class="jp-jplayer"></div>
        <div id="container" class="jp-audio" role="application" aria-label="media player">
            <div class="jp-type-playlist">
                <div class="jp-gui jp-interface">
                    <div class="jp-controls">
                        <button class="jp-previous" role="button" tabindex="0">previous</button>
                        <button class="jp-play" role="button" tabindex="0">play</button>
                        <button class="jp-next" role="button" tabindex="0">next</button>
                        <button class="jp-stop" id="sstop" role="button" tabindex="0">stop</button>
                    </div>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>
                        </div>
                    </div>
                    <div class="jp-volume-controls">
                        <button class="jp-mute" role="button" tabindex="0">mute</button>
                        <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                        <div class="jp-volume-bar">
                            <div class="jp-volume-bar-value"></div>
                        </div>
                    </div>
                    <div class="jp-time-holder">
                        <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                        <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                    </div>
                    <div class="jp-toggles">
                        <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                        <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                    </div>
                </div>
                <div class="jp-playlist">
                    <ul>
                        <li>&nbsp;</li>
                    </ul>
                </div>
                <div class="jp-no-solution">
                    <span>Update Required</span>
                    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                </div>
            </div>
        </div>
		
    </body>
</html>