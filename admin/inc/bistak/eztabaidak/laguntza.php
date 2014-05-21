<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE; ?>eztabaidak">Eztabaidak</a> > Laguntzaren testua</div>
	</div>
</div>

<form id="editatu_laguntza_form" method="post" action="<?php echo $url_base; ?>gorde_laguntza">
    <input type="hidden" name="gorde" value="BAI" />
    
    <fieldset>
        <legend><strong>Laguntzako orriaren testua</strong></legend>
        <?php CKEditor_pintatu("laguntza_testua", $laguntza->testua); ?>
    </fieldset>
	
    <div class="control-group text-center">
        <button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
        <button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
    </div>
</form>