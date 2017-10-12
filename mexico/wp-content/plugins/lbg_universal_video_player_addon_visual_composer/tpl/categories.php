<?php
$lbg_categories_update_category_record_ajax_nonce = wp_create_nonce("lbg_categories_update_category_record-special-string");
?>

<script>
function updateCategory(theCategoryID,theCategory) {
	var data ="action=lbg_categories_update_category_record&security=<?php echo $lbg_categories_update_category_record_ajax_nonce; ?>&theCategoryID="+theCategoryID+"&theCategory="+theCategory;
	
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
	});
}
</script>

<div class="wrap">
	<div id="lbg_logo">
			<h2>Manage Categories</h2>
 	</div>
    <div>
      <p>From this section you manage the categories.</p></div>

<div style="text-align:center; padding:0px 0px 20px 0px;"><img src="<?php echo plugins_url('assets/images/add_icon.gif', dirname(__FILE__))?>" alt="add" align="absmiddle" /> <a href="?page=LBG_CATEGORIES_Add_New_Category">Add new (category)</a></div>
    
<table width="100%" class="widefat">

			<thead>
				<tr>
					<th scope="col" width="8%">ID</th>
					<th scope="col" width="58%">Category Name <span style="font-size:11px; font-style:italic">(click category name to edit it)</span></th>
					<th scope="col" width="34%">Action</th>
				</tr>
			</thead>
            
<tbody>
<?php foreach ( $result as $row ) 
	{
		$row=lbg_unstrip_array($row); ?>
							<tr class="alternate author-self status-publish" valign="top">
					<td><?php echo $row['id']?></td>
					<td><div id="editme<?php echo $row['id']?>"><?php echo $row['categ']?></div>
						<script>
                        jQuery("#editme<?php echo $row['id']?>").editInPlace({
                                saving_animation_color: "#9df084",
                                callback: function(idOfEditor, enteredText, orinalHTMLContent, settingsParams, animationCallbacks) {
									enteredText=enteredText.replace(/<\S[^><]*>/g,"");
									enteredText=enteredText.replace(/&/g,"");
									if (enteredText!='') {
										animationCallbacks.didStartSaving();
										setTimeout(animationCallbacks.didEndSaving, 2000);
										updateCategory(<?php echo $row['id']?>,enteredText);
									} else {
										enteredText=orinalHTMLContent;
									}
                                    return enteredText;
                                }
                            });
                        </script>                    
                    </td>
					<td>
                        <a href="?page=LBG_CATEGORIES&id=<?php echo $row['id']?>" onclick="return confirm('Are you sure?')" style="color:#F00;">Delete</a>
                        </td>					
	            </tr>
<?php } ?>                
						</tbody>
		</table>                





</div>				