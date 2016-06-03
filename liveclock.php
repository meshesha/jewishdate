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
	var ct_<?php echo $mydata->clock_id; ?> = new Date(<?php echo ($clock_time->timezonesorce == 'gmt' ? '"' . $clock_time->clocktime . '"' : ''); ?>);
	var frmt_<?php echo $mydata->clock_id; ?> = "<?php echo $clock_time->clockformat; ?>";
	var sec_<?php echo $mydata->clock_id; ?> = <?php echo $clock_time->clockseconds; ?>;
	var lz_<?php echo $mydata->clock_id; ?> = "<?php echo $clock_time->clockleadingZeros; ?>";

	var prst_<?php echo $mydata->clock_id; ?> = new Date().getTime() - 1000;

	function clockUpdate_<?php echo $mydata->clock_id; ?>(){
		prst_<?php echo $mydata->clock_id; ?> = prst_<?php echo $mydata->clock_id; ?> + 1000;
		var jn_<?php echo $mydata->clock_id; ?> = new Date().getTime();
		var offset_<?php echo $mydata->clock_id; ?> = jn_<?php echo $mydata->clock_id; ?> - prst_<?php echo $mydata->clock_id; ?>;
		if(offset_<?php echo $mydata->clock_id; ?> > 1000){
			prst_<?php echo $mydata->clock_id; ?> = prst_<?php echo $mydata->clock_id; ?> + offset_<?php echo $mydata->clock_id; ?>;
			var ozs_<?php echo $mydata->clock_id; ?> = Math.round(offset_<?php echo $mydata->clock_id; ?> / 1000 );
			ct_<?php echo $mydata->clock_id; ?>.setSeconds(ct_<?php echo $mydata->clock_id; ?>.getSeconds() + ozs_<?php echo $mydata->clock_id; ?>);
		}

		ct_<?php echo $mydata->clock_id; ?>.setSeconds(ct_<?php echo $mydata->clock_id; ?>.getSeconds() + 1);
		var cH_<?php echo $mydata->clock_id; ?> = ct_<?php echo $mydata->clock_id; ?>.getHours();	
		var cM_<?php echo $mydata->clock_id; ?> = ct_<?php echo $mydata->clock_id; ?>.getMinutes();
		var cS_<?php echo $mydata->clock_id; ?> = ct_<?php echo $mydata->clock_id; ?>.getSeconds();

		if(frmt_<?php echo $mydata->clock_id; ?> == "t12"){
			if(cH_<?php echo $mydata->clock_id; ?> == 24){
				cH_<?php echo $mydata->clock_id; ?> = 0;
			}
			if(cH_<?php echo $mydata->clock_id; ?> < 12){
				var apm_<?php echo $mydata->clock_id; ?> = "AM";
			}
			if(cH_<?php echo $mydata->clock_id; ?> >= 12){
				var apm_<?php echo $mydata->clock_id; ?> = "PM";
				if(cH_<?php echo $mydata->clock_id; ?> > 12){
					cH_<?php echo $mydata->clock_id; ?> = cH_<?php echo $mydata->clock_id; ?> - 12;
				}
			}
		}

		if(lz_<?php echo $mydata->clock_id; ?> == 1){
			cH_<?php echo $mydata->clock_id; ?> = ( cH_<?php echo $mydata->clock_id; ?> < 10 ? "0" : "" ) + cH_<?php echo $mydata->clock_id; ?>;
			cM_<?php echo $mydata->clock_id; ?> = ( cM_<?php echo $mydata->clock_id; ?> < 10 ? "0" : "" ) + cM_<?php echo $mydata->clock_id; ?>;
			cS_<?php echo $mydata->clock_id; ?> = ( cS_<?php echo $mydata->clock_id; ?> < 10 ? "0" : "" ) + cS_<?php echo $mydata->clock_id; ?>;			
		}

		var tView_<?php echo $mydata->clock_id; ?> = cH_<?php echo $mydata->clock_id; ?> + ":" + cM_<?php echo $mydata->clock_id; ?>;

		if(sec_<?php echo $mydata->clock_id; ?>)
		{
			tView_<?php echo $mydata->clock_id; ?> = tView_<?php echo $mydata->clock_id; ?> + ":" + cS_<?php echo $mydata->clock_id; ?>;
		}

		if(frmt_<?php echo $mydata->clock_id; ?> == "t12")
		{
			tView_<?php echo $mydata->clock_id; ?> = tView_<?php echo $mydata->clock_id; ?> + " " + apm_<?php echo $mydata->clock_id; ?>;
		}

		document.getElementById("jewishClock_<?php echo $mydata->clock_id; ?>").innerHTML = tView_<?php echo $mydata->clock_id; ?>;
	}

	clockUpdate_<?php echo $mydata->clock_id; ?>();
	setInterval('clockUpdate_<?php echo $mydata->clock_id; ?>()', 1000);
</script>