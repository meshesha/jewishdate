<?php
/**
 * @package     Joomla.Site
 *
 * @copyright   Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<script type="text/javascript" >
	var ct_<?php echo $params->id; ?> = new Date(<?php echo ($clock_time->source == 'gmt' ? '"' . $clock_time->time . '"' : ''); ?>);
	var frmt_<?php echo $params->id; ?> = "<?php echo $clock_time->format; ?>";
	var sec_<?php echo $params->id; ?> = <?php echo $clock_time->seconds; ?>;
	var lz_<?php echo $params->id; ?> = "<?php echo $clock_time->leadingZeros; ?>";

	var prst_<?php echo $params->id; ?> = new Date().getTime() - 1000;

	function clockUpdate_<?php echo $params->id; ?>(){
		prst_<?php echo $params->id; ?> = prst_<?php echo $params->id; ?> + 1000;
		var jn_<?php echo $params->id; ?> = new Date().getTime();
		var offset_<?php echo $params->id; ?> = jn_<?php echo $params->id; ?> - prst_<?php echo $params->id; ?>;
		if(offset_<?php echo $params->id; ?> > 1000){
			prst_<?php echo $params->id; ?> = prst_<?php echo $params->id; ?> + offset_<?php echo $params->id; ?>;
			var ozs_<?php echo $params->id; ?> = Math.round(offset_<?php echo $params->id; ?> / 1000 );
			ct_<?php echo $params->id; ?>.setSeconds(ct_<?php echo $params->id; ?>.getSeconds() + ozs_<?php echo $params->id; ?>);
		}

		ct_<?php echo $params->id; ?>.setSeconds(ct_<?php echo $params->id; ?>.getSeconds() + 1);
		var cH_<?php echo $params->id; ?> = ct_<?php echo $params->id; ?>.getHours();	
		var cM_<?php echo $params->id; ?> = ct_<?php echo $params->id; ?>.getMinutes();
		var cS_<?php echo $params->id; ?> = ct_<?php echo $params->id; ?>.getSeconds();

		if(frmt_<?php echo $params->id; ?> == "t12"){
			if(cH_<?php echo $params->id; ?> == 24){
				cH_<?php echo $params->id; ?> = 0;
			}
			if(cH_<?php echo $params->id; ?> < 12){
				var apm_<?php echo $params->id; ?> = "AM";
			}
			if(cH_<?php echo $params->id; ?> >= 12){
				var apm_<?php echo $params->id; ?> = "PM";
				if(cH_<?php echo $params->id; ?> > 12){
					cH_<?php echo $params->id; ?> = cH_<?php echo $params->id; ?> - 12;
				}
			}
		}

		if(lz_<?php echo $params->id; ?> == 1){
			cH_<?php echo $params->id; ?> = ( cH_<?php echo $params->id; ?> < 10 ? "0" : "" ) + cH_<?php echo $params->id; ?>;
			cM_<?php echo $params->id; ?> = ( cM_<?php echo $params->id; ?> < 10 ? "0" : "" ) + cM_<?php echo $params->id; ?>;
			cS_<?php echo $params->id; ?> = ( cS_<?php echo $params->id; ?> < 10 ? "0" : "" ) + cS_<?php echo $params->id; ?>;			
		}

		var tView_<?php echo $params->id; ?> = cH_<?php echo $params->id; ?> + ":" + cM_<?php echo $params->id; ?>;

		if(sec_<?php echo $params->id; ?>)
		{
			tView_<?php echo $params->id; ?> = tView_<?php echo $params->id; ?> + ":" + cS_<?php echo $params->id; ?>;
		}

		if(frmt_<?php echo $params->id; ?> == "t12")
		{
			tView_<?php echo $params->id; ?> = tView_<?php echo $params->id; ?> + " " + apm_<?php echo $params->id; ?>;
		}

		document.getElementById("jewishClock_<?php echo $params->id; ?>").innerHTML = tView_<?php echo $params->id; ?>;
	}

	clockUpdate_<?php echo $params->id; ?>();
	setInterval('clockUpdate_<?php echo $params->id; ?>()', 1000);
</script>