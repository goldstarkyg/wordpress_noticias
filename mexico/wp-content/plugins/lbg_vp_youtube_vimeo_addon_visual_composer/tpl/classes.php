<div class="wrap">
	<div id="lbg_logo">
			<h2>Edit the CSS classes (add/delete/modify)</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-update-css-classes">
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td width="25%" align="right" valign="middle" class="row-title">The CSS Classes</td>
		    <td width="77%" align="left" valign="top"><textarea name="css_styles" id="css_styles" cols="100" rows="22"><?php echo $row['css_styles']?></textarea></td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		  </tr>
		  <tr>
		    <td width="25%" align="left" valign="middle" class="row-title"><a href="?page=LBG_CLASSES&restore=yes" onclick="return confirm('All your modifications will be lost. Are you sure you want to continue?')"  class="button-primary" style="font-weight:bold;">Restore Original</a></td>
            <td width="77%" align="left" valign="top"><input name="Submit" id="Submit" type="submit" class="button-primary" value="Update"></td>
		  </tr>
		</table>    
  </form>






</div>				